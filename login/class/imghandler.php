<?php
class ImgHandler
{

    public static function base64ToImage($base64_string, $output_file)
    {

        $ifp = fopen($output_file, "wb");

        $data = explode(',', $base64_string);

        fwrite($ifp, base64_decode($data[1]));
        fclose($ifp);

        return $output_file;
    }

    public static function putImage($imgpath, $base64_string)
    {
        try {

            //searches for existing files with same userid
            $existing = glob(pathinfo($imgpath, PATHINFO_DIRNAME)."/".pathinfo($imgpath, PATHINFO_FILENAME)."*");

            if (is_writable(pathinfo($imgpath, PATHINFO_DIRNAME))) {

                foreach ($existing as $file) {
                    //deletes existing files with same userid
                    unlink($file) ;
                }

                if (!self::base64ToImage($base64_string, $imgpath)) {

                    throw new Exception("<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Image upload failed</div>");

                } else {
                    //success
                    return "<div class=\"alert alert-success alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Changes saved!</div>";

                }

            } else {

                throw new Exception("<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Image directory not writable</div>");

            }

        } catch (Exception $e) {

            return $e->getMessage();

        }
    }
}
