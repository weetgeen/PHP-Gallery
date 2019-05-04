<?php
                                                
    $imageFolder = 'images/';
                                                
    function echoImage($source,$href,$title)
    {
        echo '<div class="col-4">';
        if($title != '')
        {
            echo '<h3>'.$title.'</h3> 
                  <span class="image fit">
                  <a href="'.$href.'" class="image fit thumb"><img src="'.$source.'" alt="" /></a>
                  </span></div>';
        }
        else
        {
            echo '<span class="image fit">
                  <a href="'.$href.'" class="image fit thumb" target="_blank"><img src="'.$source.'" alt="" /></a>
                  </span></div>';
        }
    }             
    
    function scanCurrentFolderForFolders($currentFolder,$recursion)
    {
        $dirs = array_filter(glob($currentFolder.'*'), 'is_dir');//arrays every folder in $currentFolder

        foreach($dirs as $dir)
        {
            if($recursion)
			{
				$folders = explode("//",$currentFolder);
				$folder = $folders[1];
				$folder = str_replace('/','',$folder);
			}
			else
			{
				$folder = str_replace($currentFolder, '', $dir); //removes ..$imgeFolder from the filepath and leaves just the folders name
			}	
			
			if (is_dir($dir) && $folder != 'thumbnails')     //TODO Add addiditional folders that can be skipped
            {
                $images = glob($dir."/*.{jpg,JPG,jpeg,png,gif,bmp}",GLOB_BRACE);
       
				if($images)
				{
					foreach($images as $image) 
					{
						//Echos one picture with name and link to album for each directorty in /portofolio
						echoImage($image,"gallery.php?album=".$_GET['album'].'/'.$folder,$folder);
						break; // breaks so only one image shows up as thumbnail of the album
					}
				}
				else
				{
					$recursion = TRUE;
					//Search for nest folder and echo that image
					scanCurrentFolderForFolders($dir.'/',$recursion);					
					$recursion = FALSE;
				}
            }
        }    
    }
    function echoTitleofCurrentFolder($folder)
    {
                  echo '</div></div><div class="box alt">
                        <div class="row gtr-50 gtr-uniform">
                        <div class="col-4"><h3>'.$folder.'</h3></div></div></div>';
                        
    }
                                           
        $currentFolder= $imageFolder.$_GET['album'].'/';
        scanCurrentFolderForFolders($currentFolder,$recursion = FALSE);
  

        
        $dir = $imageFolder.$_GET['album']; //Gets the selected album
        if (is_dir($dir))
        {    
            
            $folder = str_replace($imageFolder, '', $dir);
            echoTitleOfCurrentFolder($folder);
            echo'<div class="box alt">
                 <div class="row gtr-50 gtr-uniform">';
            $images = glob($dir."/*.{jpg,JPG,jpeg,png,gif,bmp}",GLOB_BRACE); //arrays all images from album
            foreach($images as $image)
            {
                //echos each image in the current folder
                $uncompressedimage = str_replace('.LIVE/', '', $image);  //Removes .LIVE/ so link is to uncompressed image
                echoImage($image,$uncompressedimage,'');
            }
        }

?>
