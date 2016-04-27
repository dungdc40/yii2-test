<?php

namespace backend\models;

use yii\web\UploadedFile;
use creocoder\taggable\TaggableBehavior;
use backend\models\ItemsQuery;
use yii\db\Expression;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
/**
 * This is the model class for table "items".
 *
 * @property integer $id
 * @property string $item_name
 * @property integer $vendor
 * @property integer $type
 * @property string $serial_number
 * @property double $price
 * @property double $weight
 * @property string $color
 * @property string $release_date
 * @property string $photo
 * @property string $tags
 * @property string $created_date
 *
 * @property Vendors $vendor0
 * @property Types $type0
 */
class Items extends \yii\db\ActiveRecord
{

	public $file;
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'items';
	}

	public function behaviors()
	{
		return [
			'taggable' => [
				'class' => TaggableBehavior::className(),
			// 'tagValuesAsArray' => false,
			// 'tagRelation' => 'tags',
			// 'tagValueAttribute' => 'name',
			// 'tagFrequencyAttribute' => 'frequency',
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['item_name', 'vendor_id', 'type_id', 'serial_number', 'price', 'weight', 'color', 'release_date'], 'required'],
			[['vendor_id', 'type_id'], 'integer'],
			[['price', 'weight'], 'number'],
			[['file'], 'image'],
			[['item_name'], 'string', 'max' => 255],
			[['serial_number', 'color'], 'string', 'max' => 125],
			[['release_date', 'created_date', 'tagValues'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'item_name' => 'Item Name',
			'vendor' => 'Vendor Name',
			'type' => 'Type',
			'serial_number' => 'Serial Number',
			'price' => 'Price',
			'weight' => 'Weight',
			'color' => 'Color',
			'release_date' => 'Release Date',
			'file' => 'Photo',
			'tagValues' => 'Tags',
			'created_date' => 'Created Date',
		];
	}
	
	public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
	
	public static function find()
    {
        return new ItemsQuery(get_called_class());
    }

    public function getTags()
    {
        return $this->hasMany(Tags::className(), ['id' => 'tag_id'])
            ->viaTable('{{%item_tag_assn}}', ['item_id' => 'id']);
    }
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getVendor()
	{
		return $this->hasOne(Vendors::className(), ['id' => 'vendor_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getType()
	{
		return $this->hasOne(Types::className(), ['id' => 'type_id']);
	}


	/**
	 * Save item
	 */
	public function saveItem()
	{
		
		$this->created_date = new Expression('NOW()');
		$this->file = UploadedFile::getInstance($this, 'file');

		if ($this->file) {
			// get the instance of the upladed file
			$photoPath = 'images/' . time() . '.' . $this->file->extension;
			$this->photo = $photoPath;			
		}
				
		
		if ($this->save()) {
			$this->removeAllTagValues();
			
			if($this->tagValues) {	
				$this->addTagValues($this->tagValues);				
			}
			
			if ($this->file) {
				$this->file->saveAs($photoPath);
			}
			return true;
		}
		
		return false;
	}
	
	/*
	 * count total number of items
	 */
	public static function getTotalCount()
	{
		return static::find()->count();
	}
	
	public static function getAveragePrice()
	{
		$query = new Query;
		return $query->from('items')->average('price');
	}
	
	public static function getPercentageItemsPerType()
	{
		$colors = ['#f56954', '#00a65a', '#f39c12'];
		$types =  Yii::$app->db->createCommand('SELECT types.name AS type, '
				. '(COUNT(*) / (SELECT COUNT(*) FROM items)) * 100 AS percentage FROM items '
				. 'INNER JOIN types ON items.type_id=types.id GROUP BY type')->queryAll();
		foreach ($types as $index => $type) {
			$types[$index]['color'] = $types[$index]['highlight'] = array_pop($colors);
		}
		return $types;
	}
	
	public static function getLastestItems($limit)
	{
		$data = [];
		
		$items =  static::find()
				->orderBy('created_date DESC')
				->limit($limit)
				->with('vendor', 'type', 'tags')
				->all();
		
		;
		foreach ($items as $index => $item) {
			$data[$index] = [
				'item_name' => $item->item_name,
				'price' => $item->price,
				'tags' => array_reduce($item->tags, function($tags, $tag) {
					if(!$tags) {
						$tags = $tag->name;
					} else {
						$tags .= ', '.$tag->name;
					}
					
					return $tags;
				}, ''),
				'item_photo' => $item->photo,
				'vandor_name' => $item->vendor->name,
				'vendor_photo' => $item->vendor->logo
			];
		}
				
		
		$provider = new ArrayDataProvider([
			'allModels' => $data
		]);
		
		return $provider;
	}
}
