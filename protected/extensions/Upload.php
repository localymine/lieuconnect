<?php

/**
 * 
 * @version 1.0
 * 
 * components
 * 
 * 'upload' => array(
            'class' => 'application.extensions.Upload',
        ),
 * 
 */
class Upload {

    public $folder;
    public $userModel;  //the model of the user
    public $userPixColumn;  //the column to save the picture filename
    public $post;
    public $old_file;
    public $ThumbSquareSize = 256; //Thumbnail will be 200x200
    public $BigImageMaxSize = 512; //Image Maximum height or width
    public $ThumbPrefix = "thumb_"; //Normal thumb Prefix
    public $Quality = 90; //jpeg quality

    public function init() {
    }

    public function process() {

        if (isset($this->post)) {

            // Random number will be added after image name
            $RandomNumber = rand(0, 9999999999);

            $ImageName = str_replace(' ', '-', strtolower($this->post->name)); //get image name
            $ImageSize = $this->post->size; // get original image size
            $TempSrc = $this->post->tempName; // Temp name of image file stored in PHP tmp folder
            $ImageType = $this->post->type; //get file type, returns "image/png", image/jpeg, text/plain 			
            //Let's check allowed $ImageType, we use PHP SWITCH statement here
            switch (strtolower($ImageType)) {
                case 'image/png':
                    //Create a new image from file 
                    $CreatedImage = imagecreatefrompng($TempSrc);
                    break;
                case 'image/gif':
                    $CreatedImage = imagecreatefromgif($TempSrc);
                    break;
                case 'image/jpeg':
                case 'image/pjpeg':
                    $CreatedImage = imagecreatefromjpeg($TempSrc);
                    break;
                default:
                    die('Unsupported File!'); //output error and exit
            }

            //PHP getimagesize() function returns height/width from image file stored in PHP tmp folder.
            //Get first two values from image, width and height. 
            //list assign svalues to $CurWidth,$CurHeight
            list($CurWidth, $CurHeight) = getimagesize($TempSrc);

            //Get file extension from Image name, this will be added after random name
            $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt = str_replace('.', '', $ImageExt);

            //remove extension from filename
            $ImageName = preg_replace("/\\.[^.\\s]{3,4}$/", "", $ImageName);

            //Construct a new name with random number and extension.
            $NewImageName = $ImageName . '-' . $RandomNumber . '.' . $ImageExt;

            //set the Destination Image
            $thumb_DestRandImageName = $this->folder . $this->ThumbPrefix . $NewImageName;

            //Thumbnail name with destination directory
            $DestRandImageName = $this->folder . $NewImageName; // Image with destination directory
            //Resize image to Specified Size by calling resizeImage function.
            if ($this->resizeImage($CurWidth, $CurHeight, $this->BigImageMaxSize, $DestRandImageName, $CreatedImage, $this->Quality, $ImageType)) {
                //Create a square Thumbnail right after, this time we are using cropImage() function
                if (!$this->cropImage($CurWidth, $CurHeight, $this->ThumbSquareSize, $thumb_DestRandImageName, $CreatedImage, $this->Quality, $ImageType)) {
                    echo 'Error Creating thumbnail';
                }
                /*
                  We have succesfully resized and created thumbnail image
                  We can now output image to user's browser or store information in the database
                 */

                $this->deleteOld();

                return $NewImageName;
                /*
                 * Here you can save image details into the datase							
                 */

                //SAVE INFO INTO DATABASE	
//                    $module = Yii::app()->getModule('ajaxuploader');
//
//                    CActiveRecord::model($module->userModel)->updateByPk(Yii::app()->user->id, array($module->userPixColumn => $NewImageName));
//
//                    $original = $NewImageName;  //original size of image
//                    $resized = $ThumbPrefix . $NewImageName; //resized image
//                    // output for users
//                    $output = $this->renderPartial('_view', array('source' => $DestinationDirectory, 'original' => $original, 'resized' => $resized));
//
//                    Yii::app()->clientScript->renderBodyEnd($output);
//
//                    //pass the result of $output to the calling ajax function
//                    echo $output;
//                    Yii::app()->end();
            }//end resize function call
        }
    }

    // This function will proportionally resize image 
    public function resizeImage($CurWidth, $CurHeight, $MaxSize, $DestFolder, $SrcImage, $Quality, $ImageType) {
        //Check Image size is not 0
        if ($CurWidth <= 0 || $CurHeight <= 0) {
            return false;
        }

        //Construct a proportional size of new image
        $ImageScale = min($MaxSize / $CurWidth, $MaxSize / $CurHeight);
        $NewWidth = ceil($ImageScale * $CurWidth);
        $NewHeight = ceil($ImageScale * $CurHeight);
        $NewCanves = imagecreatetruecolor($NewWidth, $NewHeight);

        // Resize Image
        if (imagecopyresampled($NewCanves, $SrcImage, 0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight)) {
            switch (strtolower($ImageType)) {
                case 'image/png':
                    imagepng($NewCanves, $DestFolder);
                    break;
                case 'image/gif':
                    imagegif($NewCanves, $DestFolder);
                    break;
                case 'image/jpeg':
                case 'image/pjpeg':
                    imagejpeg($NewCanves, $DestFolder, $Quality);
                    break;
                default:
                    return false;
            }
            //Destroy image, frees memory
            if (is_resource($NewCanves)) {
                imagedestroy($NewCanves);
            }
            return true;
        }
    }

    //This function corps image to create exact square images, no matter what its original size!
    public function cropImage($CurWidth, $CurHeight, $iSize, $DestFolder, $SrcImage, $Quality, $ImageType) {
        //Check Image size is not 0
        if ($CurWidth <= 0 || $CurHeight <= 0) {
            return false;
        }

        //abeautifulsite.net has excellent article about "Cropping an Image to Make Square bit.ly/1gTwXW9
        if ($CurWidth > $CurHeight) {
            $y_offset = 0;
            $x_offset = ($CurWidth - $CurHeight) / 2;
            $square_size = $CurWidth - ($x_offset * 2);
        } else {
            $x_offset = 0;
            $y_offset = ($CurHeight - $CurWidth) / 2;
            $square_size = $CurHeight - ($y_offset * 2);
        }

        $NewCanves = imagecreatetruecolor($iSize, $iSize);
        if (imagecopyresampled($NewCanves, $SrcImage, 0, 0, $x_offset, $y_offset, $iSize, $iSize, $square_size, $square_size)) {
            switch (strtolower($ImageType)) {
                case 'image/png':
                    imagepng($NewCanves, $DestFolder);
                    break;
                case 'image/gif':
                    imagegif($NewCanves, $DestFolder);
                    break;
                case 'image/jpeg':
                case 'image/pjpeg':
                    imagejpeg($NewCanves, $DestFolder, $Quality);
                    break;
                default:
                    return false;
            }
            //Destroy image, frees memory	
            if (is_resource($NewCanves)) {
                imagedestroy($NewCanves);
            }
            return true;
        }
    }

    private function deleteOld() {
        if ($this->old_file != '') {
            $filename = $this->folder . $this->old_file;
            $thumb_filename = $this->folder . 'thumb_' . $this->old_file;
            if (file_exists($filename)) {
                unlink($filename);
            }
            if (file_exists($thumb_filename)) {
                unlink($thumb_filename);
            }
        }
    }

    public function normal() {

        if (isset($this->post)) {

            // Random number will be added after image name
            $RandomNumber = rand(0, 9999999999);

            $ImageName = str_replace(' ', '-', strtolower($this->post->name)); //get image name
            $ImageSize = $this->post->size; // get original image size
            $TempSrc = $this->post->tempName; // Temp name of image file stored in PHP tmp folder
            $ImageType = $this->post->type; //get file type, returns "image/png", image/jpeg, text/plain 			
            //Let's check allowed $ImageType, we use PHP SWITCH statement here
            switch (strtolower($ImageType)) {
                case 'image/png':
                    //Create a new image from file 
                    $CreatedImage = imagecreatefrompng($TempSrc);
                    break;
                case 'image/gif':
                    $CreatedImage = imagecreatefromgif($TempSrc);
                    break;
                case 'image/jpeg':
                case 'image/pjpeg':
                    $CreatedImage = imagecreatefromjpeg($TempSrc);
                    break;
                default:
                    die('Unsupported File!'); //output error and exit
            }

            //Get file extension from Image name, this will be added after random name
            $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt = str_replace('.', '', $ImageExt);

            //remove extension from filename
            $ImageName = preg_replace("/\\.[^.\\s]{3,4}$/", "", $ImageName);

            //Construct a new name with random number and extension.
            $NewImageName = $ImageName . '-' . $RandomNumber . '.' . $ImageExt;

            $DestRandImageName = $this->folder . $NewImageName; // Image with destination directory

            if (move_uploaded_file($TempSrc, $DestRandImageName)) {
                $this->deleteOld();
            }

            return $NewImageName;
        }
    }

}
