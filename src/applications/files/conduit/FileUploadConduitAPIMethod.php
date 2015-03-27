<?php

final class FileUploadConduitAPIMethod extends FileConduitAPIMethod {

  public function getAPIMethodName() {
    return 'file.upload';
  }

  public function getMethodDescription() {
    return 'Upload a file to the server.';
  }

  public function defineParamTypes() {
    return array(
      'data_base64' => 'required nonempty base64-bytes',
      'name' => 'optional string',
      'viewPolicy' => 'optional valid policy string or <phid>',
      'canCDN' => 'optional bool',
    );
  }

  public function defineReturnType() {
    return 'nonempty guid';
  }

  public function defineErrorTypes() {
    return array(
    );
  }

  protected function execute(ConduitAPIRequest $request) {
    $viewer = $request->getUser();

    $name = $request->getValue('name');
    $can_cdn = $request->getValue('canCDN');
    $view_policy = $request->getValue('viewPolicy');

    $data = $request->getValue('data_base64');
    $data = $this->decodeBase64($data);

    $file = PhabricatorFile::newFromFileData(
      $data,
      array(
        'name' => $name,
        'authorPHID' => $viewer->getPHID(),
        'viewPolicy' => $view_policy,
        'canCDN' => $can_cdn,
        'isExplicitUpload' => true,
      ));

    return $file->getPHID();
  }

}
