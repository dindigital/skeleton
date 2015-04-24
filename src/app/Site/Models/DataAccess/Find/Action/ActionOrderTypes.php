<?php

namespace Site\Models\DataAccess\Find\Action;

class ActionOrderTypes
{

  const DATE_DESC = 'date DESC';
  const SEQUENCE_ASC = 'sequence=0,sequence';
  const SEQUENCE_DESC = 'action.sequence=0, action.sequence, action.date DESC';

}
