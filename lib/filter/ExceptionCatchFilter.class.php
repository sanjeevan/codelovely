<?php

class ExceptionCatchFilter extends sfFilter
{
  public function execute($filter_chain)
  {
    try {
      $filter_chain->execute();
    } catch (sfStopException $e) {
      throw $e;
    } catch (Exception $e) {
      throw $e;
    }
  }
}