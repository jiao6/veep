<?php

class RoleRight{
    //每页数据数量
    private $id = 0;//总记录数
    private $name   = "";
    private $roleId    = 0;
    private $activityId    = 0;//当前页号
    private $operationId   = 0;//前、后若干页

    /* 构造函数。总记录数量、每页记录数、当前页号  */
    function __construct($id, $name, $roleId, $activityId, $operationId){
        $this->id = $id;
        $this->name = $name;
        $this->roleId =    	$roleId;
        $this->activityId =	$activityId;
        $this->operationId= $operationId;
    }

    function getId() {
        return $this->id;
    }
    function setId($id) {
        $this->id = $id;
    }

    function getName() {
        return $this->name;
    }
    function setname($name) {
        $this->name = $name;
    }


    function getRoleId() {
        return $this->roleId;
    }
    function setRoleId($roleId) {
        $this->roleId = $roleId;
    }
    
    function getActivityId() {
        return $this->activityId;
    }
    function setActivityId($activityId) {
        $this->activityId = $activityId;
    }


    function getOperationId() {
        return $this->operationId;
    }
    function setOperationId($operationId) {
        $this->operationId = $operationId;
    }


    function toString() {
    }
}
?>
