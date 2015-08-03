<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Book;

/**
 * BookSearch represents the model behind the search form about `app\models\Book`.
 */
class BookSearch extends Book
{
   public $authFullName;
   public $beginDate;
   public $endDate;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'author_id'], 'integer'],
            [['name', 'date_create', 'date_update', 'preview', 'date'], 'safe'],
            [['authFullName'], 'safe'],
            [['beginDate', 'endDate', ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        $query = Book::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name',
                'preview',
                'date',
                'date_create',
                'authFullName' => [
                    'asc' => ['authors.firstname' => SORT_ASC],
                    'desc' => ['authors.lastname' => SORT_DESC],
                    'label' => 'Автор'
                ]
            ]
        ]);


        if (!($this->load($params) && $this->validate())) {

             $query->joinWith(['author']);
              return $dataProvider;

        }


        $query->andFilterWhere([
            'id' => $this->id,
            'date_create' => $this->date_create,
            'date_update' => $this->date_update,
            'author_id' => $this->author_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'preview', $this->preview]);


       $query->joinWith(['author' => function ($q) {
               $q->where('authors.firstname LIKE "%' . $this->authFullName . '%"');
           }]);


        $query->andFilterWhere(['between', 'books.date', $this->beginDate, $this->endDate ]);


        return $dataProvider;
    }
}
