<?php

namespace sol42\LaravelOnlyoffice\Interfaces;

interface IOnlyofficeService
{
  public static function prepareDocumentInfo(string $docPath): array;

  public static function save(string $fileUrl, string $key);
}
