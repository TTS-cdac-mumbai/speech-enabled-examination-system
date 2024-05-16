<?php

class Translator {
  private $language;
  private $translations;
  private $langgg;

  public function __construct($medium) {
    $this->language = $this->getLocaleCodeForDisplayLanguage($medium);
    $this->loadTranslations();
  }
  
  private function getLocaleCodeForDisplayLanguage($name){
      $languageCodes = array(
      "en" => "english",
      "hi" => "hindi",
      "mr" => "marathi"
      );
      return array_search($name, $languageCodes);
  }

  public function getLanguageCode($name) {
    return $this->getLocaleCodeForDisplayLanguage($name);
}
  
  
  private function loadTranslations() {
    $filePath = "./language/{$this->language}.json";
    $jsonContent = file_get_contents($filePath);
    $this->translations = json_decode($jsonContent, true);
  }

  public function translate($key) {
    return isset($this->translations[$key]) ? $this->translations[$key] : $key;
  }


}

?>