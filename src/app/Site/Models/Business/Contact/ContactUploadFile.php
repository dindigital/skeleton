<?php

namespace Site\Models\Business\Contact;

use Exception;

class ContactUploadFile
{

    private $file_name;
    private $file_type;
    private $file_tmp;
    private $extensions;
    private $mimes;
    private $path;

    public function __construct ( $file )
    {
        $this->file_name = $file['name'];
        $this->file_type = $file['type'];
        $this->file_tmp = $file['tmp_name'];
        $this->extensions = array('pdf', 'doc', 'odt', 'docx');
        $this->mimes = array('application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.oasis.opendocument.text');
        $this->path = "public/system/uploads/resume/";

    }

    public function upload ()
    {
        //so faz o upload se a extensao e o mime estiver na lista - analisa o arquivo temporario antes de enviar
        if ( $this->extension_valid() && $this->myme_type() ) {
            //cria um nome de arquivo unico
            $newfilename = $this->create_file_name();
            $uploadfile = $newfilename;

            if ( move_uploaded_file($this->file_tmp, $uploadfile) ) {
                return $this->get_new_file_name($newfilename);
            } else {
                throw new \Exception("Ocorreu um erro durante o upload");
            }
        } else {
            throw new \Exception("O formato do arquivo enviado não é um formato aceito pelo sistema, por favor envie um PDF ou documento do Word");
        }

    }

    public function get_new_file_name ( $file_path )
    {
        $name = explode("/", $file_path);
        $name = $name[count($name) - 1];
        return $name;

    }

    public function create_file_name ()
    {
        $extension = explode(".", $this->file_name);
        $extension = strtolower($extension[count($extension) - 1]);
        $prefix = "mori_cv_" . date("YmdHis", time()) . "_";
        $tempnam = tempnam($this->path, $prefix);
        unlink($tempnam);
        return $tempnam . '.' . $extension;

    }

    public function myme_type ()
    {
        $file_info = new \finfo(FILEINFO_MIME);
        $mime_type = $file_info->buffer(file_get_contents($this->file_tmp));
        $mime_type = explode(";", $mime_type);
        $mime_type = $mime_type[0];

        if ( in_array($mime_type, $this->mimes) ) {
            return true;
        } else {
            return false;
        }

    }

    public function extension_valid ()
    {
        $extension = explode(".", $this->file_name);
        $extension = strtolower($extension[count($extension) - 1]);

        if ( in_array($extension, $this->extensions) ) {
            return true;
        } else {
            return false;
        }

    }

}
