require 'rubygems'
require 'sinatra'
#require 'sinatra/activerecord'
#require 'active_record'
require 'uri'
require 'twilio-ruby'
require 'net/http'
require 'json'
require "securerandom"

require 'data_mapper'
#puts ENV.inspect
#puts ENV['HEROKU_POSTGRESQL_GREEN_URL']
DataMapper.setup(:default, ENV['HEROKU_POSTGRESQL_GREEN_URL'])

$stdout.sync = true

helpers do

  def send_event(data)

      puts "DATA: #{data}"
      
      if(data == nil)
        return true
      end
      
      data["_domain"] = "connectFour"
      
      puts "DATA: #{data}"
      
      connect_four_url = "http://beebe.asuscomm.com:8080/connectFour/index.php/event/consume"
      #connect_four_url = "http://consumer.eventedapi.org/receive/f1d372ce-53e7-4887-ae2e-79365e36d3be"

      uri = URI.parse(connect_four_url)
      
      http = Net::HTTP.new(uri.host, uri.port)
      #http.use_ssl = true
      #http.verify_mode = OpenSSL::SSL::VERIFY_NONE
      req = Net::HTTP::Post.new(uri.path)
      req.set_form_data(data)
      resp = Net::HTTP.new(uri.host, uri.port).start { http.request(req) }
      
      puts "Response: #{resp.body}"
      
      return JSON.parse(resp.body)
  end
  
  def send_text(player, body)
      # Twilio account information
    account_sid = 'AC01b75f19371b4aa5fd29299a095dcc8e'
    auth_token = '562c57324d2e9099ba2515f2d1ef57be'
    account_number = '+18019214233'
    twilio_client = Twilio::REST::Client.new account_sid, auth_token
    puts "Sending #{body}"
    sms = twilio_client.account.sms.messages.create(:body =>body,
      :to => player.phone_number,
      :from => account_number)
      
  end
  
  def display_board(board)
    text_body = ""
    #for colName in 0..4
    #  text_body << "[#{colName}]"
    #end
    chars = [0,1,2,3,4]
    text_body << chars.join("|")
    text_body << "\n"
    for row in 0..4
      chars = []
      for col in 0..4
        if board[col][row] == 1
          chars.push("x")
        elsif board[col][row] == 0
          chars.push("o")
        elsif board[col][row] == -1
          chars.push("  ")
        end
        #text_body << "[#{char}]"
              puts "chars: #{chars}"
      end
      text_body << chars.join("|")
      text_body << "\n"
    end
    return text_body
  end
end


class Player
  include DataMapper::Resource

  property :id,    Serial
  property :phone_number, String
  property :esl, String
end
#Player.auto_migrate!
Player.auto_upgrade!

post '/text' do
    #puts "Twilio Parameters: #{params.inspect}"

   sender = params[:From]
   body = params[:Body]

    puts "Phone Number: #{sender}"
    puts "Body: #{body}"

    # find players that correspond to phone number
    player = Player.first(:phone_number => sender)

    if player
      puts "Player exists: #{player.inspect}"
      # if a player exists
      if /^\d$/.match(body)
        column = body
        data =  {'_name' => '_makeMove',
          'esl' => url("/connectFour/#{player.esl}"),
          'number' => column
        }
        response = send_event( data )
        if !(response["success"])
        send_text(player, response["message"])
      end

      elsif /^chat (.*)/.match(body.downcase)
        puts "want to chat"
        message = /chat (.*)/.match(body.downcase)[1]
        data =  {'_name' => '_sendChat',
          'esl' => url("/connectFour/#{player.esl}"),
          'message' => message
        }
        response = send_event( data )
        if !(response["success"])
          send_text(player, response["message"])
          end
      elsif /^start/.match(body.downcase)
        puts "want to start game"
        #human = (/start (0|1)/.match(body.downcase)[1] == 1) ? true : false
        human = true
        data =  {'_name' => '_startGame',
          'esl' => url("/connectFour/#{player.esl}"),
          'human' => human
        }
        response = send_event(data)
        if !(response["success"])
          send_text(player, response["message"])
        end
      elsif /^disp/.match(body.downcase)
        puts "display board"
        data =  {'_name' => '_boardRequest',
          'esl' => url("/connectFour/#{player.esl}")
        }
        response = send_event(data)
        
        board = response["board"]
        player_num = response["player"] == 0 ? "o" : "x" # 0 or 1
        myTurn = response["isMyTurn"] == "Y"
        text_body = "P: #{player_num} #{myTurn ? "your turn" : "not your turn"}\n"
        text_body << display_board(board)
      #  for colName in 0..4
       #   text_body << "[#{colName}]"
       # end
        #for row in 0..4
         # for col in 0..4
          #  char = ""
           # if board[col][row] == 1
            #  char = "X"
            #elsif board[col][row] == 0
             # char = "O"
            #elsif board[col][row] == -1
            #  char = "  "
           # end
          #  text_body << "[#{char}]"
         # end
        #  text_body << "\n"
       # end
        send_text(player, text_body)
      else
        # notify user
        text_body1 = "Invalid command. To start game with a human, text START. To place chip, text the column number. To display board, text DISP."
        send_text(player,text_body1)
      end
    else
      # if player doesn't exist
      #create new player
      @esl = SecureRandom.urlsafe_base64()
      while (Player.first(:esl => @esl) != nil)
        @esl = SecureRandom.urlsafe_base64()
      end
      
      player = Player.create(:phone_number => sender, :esl => @esl)

      # send instructional text(s)
      text_body1 = "Welcome to Connect Four!  To start game with a human, text START. To place chip, text the column number. To display board, text DISP."
      send_text(player,text_body1)
         
    end
end

post '/connectFour/:esl_id' do
  puts "Params: #{params.inspect}"
  
  player = Player.first(:esl => params[:esl_id])
  puts "Player: #{player.inspect}"
  
  if(player == nil)
    return
  end
  
  name = params[:_name]
  
  if(name == "_moveMade")
    board = JSON.parse(params[:board])
    text_body = display_board(board)
  elsif(name == "_gameStarted")
    text_body = "Let the games begin"
  elsif(name == "_gameEnded")
    won = params[:victory]
    if(won == "1")
      text_body = "You won!"
    else
      text_body = "You lost."
    end
  elsif(name == "_error")
    text_body = params[:message]
  elsif(name == "_chatReceived")
    text_body = params[:message]
  else
    return
  end
  send_text(player, text_body)

end
