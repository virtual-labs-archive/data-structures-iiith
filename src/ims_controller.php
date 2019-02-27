<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use ZipArchive;


class ims_controller extends Controller
{

    public function pending_request_ca(Request $request)
    {
        $x=DB::select("select * from div_req");
        foreach($x as $d)
        {
            echo '<html> <p>req_id='.$d->{'req_id'}.'</p>
                  <p>discription='.$d->{'req_content'}.'</p>';
            $data=DB::select("select * from admin_req where req_id=".$d->{'req_id'});
            foreach($data as $q){
                
                echo '  <p>uid='.$q->{'uid'}.'<p>
                        <p>reply content'.$q->{'rep_content'}.'</p>
                </html>';
            }
        }
    }
    public function responses(Request $request)
    {
        $uid=$request->session()->get('uid');
        $x=DB::select("select * from div_req where uid=$uid;");
        echo "comepleted request<br>";
        echo "<br><b>To be processed by admin</b><br>";
        $data = DB::select("select * from div_req d where NOT EXISTS(select req_id from admin_req where req_id=d.req_id)");
        foreach($data as $row){
            $id = $row->{'req_id'};
            $req_content = $row->{'req_content'};
            echo "<p>id=$id</p>
                  <p>content=$req_content</p>";
        }
        foreach($x as $d){
            $id=$d->{'req_id'};
            $data=DB::select("select req_id,count(*) as c from admin_req where req_id=".$id." and rep_content!='None' group by req_id;");
            #echo sizeOf($data);
            #print_r($data);
            if(sizeOf($data)!=0 and $d->{'count_n'}==$data[0]->{'c'})
            {
                echo '
            <p>id='.$id.'</p>
            <p>content='.$d->{'req_content'}.'</p>';

                echo '<a href="'.URL::to('/download_files/'.$id.'').'">click here to download</a>';
            }
            
        }
        echo "pending request<br>";
        foreach($x as $d){
            $id=$d->{'req_id'};
            $data=DB::select("select req_id,count(*) as c from admin_req where req_id=".$id." and rep_content!='None' group by req_id;");
            #echo sizeOf($data);
            #print_r($data);
            

            if(sizeOf($data)!=$d->{'count_n'})
            {
                echo '
            <p>id='.$id.'</p>

            <p>content='.$d->{'req_content'}.'</p>';
                echo "<p>not yet received</p>";
            }
        }
    }
    public function download_files($id)
    {
        $data=DB::select('select distinct rep_content from admin_req where req_id='.$id.';');
        #print_r($data);
        $arr=array();
        foreach($data as $d)
        {
            array_push($arr,$d->{'rep_content'});
        }
        print_r($arr);
        
       $zip = new ZipArchive();
    //create the file and throw the error if unsuccessful
    $archive_file_name=time().".zip";
    $file_path="H:/xampp/htdocs/IMSP/attachments/";
	if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
    	exit("cannot open <$archive_file_name>");
	}
	//add each files of $file_name array to archive
	foreach($arr as $files)
	{
  		$zip->addFile($file_path.$files,$files);
		//echo $file_path.$files,$files."
	}
	$zip->close();
	//then send the headers to foce download the zip file
	header("Content-type: application/zip"); 
	header("Content-Disposition: attachment; filename=$archive_file_name"); 
	header("Pragma: no-cache"); 
	header("Expires: 0"); 
	readfile("$archive_file_name");
    }
}
