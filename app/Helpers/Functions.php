<?php
use App\Models\Group;

function getAllGroups(){
  $groups = new Group;

  return $groups->getAll();
}