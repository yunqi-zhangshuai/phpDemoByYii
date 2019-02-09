<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 19-2-8
 * Time: 下午10:03
 */

class LinkNode
{

    private $data ;
    /**
     * @var $next self
     */
    private $next;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }

    public function __get($name)
    {
        return $this->{$name};
    }
}

class LinkList
{
    private $head;
    private $length;

    public function insert($data)
    {
        $newNode = new LinkNode($data);
        if($this->head === null){
            $this->head = $newNode;
        }else{
            /**
             * @var $currentNode LinkNode;
             */
            $currentNode = $this->head;
            while ($currentNode->next !== null) {
                $currentNode = $currentNode->next;
            }

            $currentNode->next = $newNode;
        }

        $this->length++;
        return true;
    }


    public function getHeader()
    {
        print_r($this->head);
    }
}

$linkList = new LinkList();
 for ($i = 0 ; $i<5;$i++) {
     $linkList->insert($i*2);
 }
$linkList->getHeader();


