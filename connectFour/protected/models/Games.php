<?php

/**
 * This is the model class for table "games".
 *
 * The followings are the available columns in table 'games':
 * @property integer $id
 * @property string $board
 * @property integer $player1_id
 * @property integer $player2_id
 * @property integer $whosTurn
 * @property string $active
 *
 * The followings are the available model relations:
 * @property Players $player1
 * @property Players $player2
 */
class Games extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Games the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'games';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('player1_id, player2_id, whosTurn', 'numerical', 'integerOnly'=>true),
			array('board', 'length', 'max'=>255),
			array('active', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, board, player1_id, player2_id, whosTurn, active', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'player1' => array(self::BELONGS_TO, 'Players', 'player1_id'),
			'player2' => array(self::BELONGS_TO, 'Players', 'player2_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'board' => 'Board',
			'player1_id' => 'Player1',
			'player2_id' => 'Player2',
			'whosTurn' => 'Whos Turn',
			'active' => 'Active',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('board',$this->board,true);
		$criteria->compare('player1_id',$this->player1_id);
		$criteria->compare('player2_id',$this->player2_id);
		$criteria->compare('whosTurn',$this->whosTurn);
		$criteria->compare('active',$this->active,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}