/*global Qiniu */
/*global plupload */
/*global FileProgress */
/*global hljs */
var out_str = '';
function qiniu_upload_update_db2(){
	if(out_str==''){
		return;
	}
	console.info(out_str);
	var arr = out_str.split('|,|');
	$.get('/camerist/setpics?uid='+_uid+'&key='+arr[1],function(data){
		loading.hide();
		doing = false;
		$('.cbanner').css({"background-image":"url("+data+")"});
		console.info('finish');
		out_str='';
	});
}
$(function() {
    var uploader = Qiniu.uploader({
        runtimes: 'html5,flash,html4',
        browse_button: 'pickfiles',
        container: 'container',
        drop_element: 'container',
        max_file_size: '100mb',
        flash_swf_url: 'plupload/Moxie.swf',
        dragdrop: true,
        chunk_size: '4mb',
        multi_selection: false,
        uptoken_url: $('#uptoken_url').val(),
        domain: $('#domain').val(),
        get_new_uptoken: false,
		unique_names: true,
        auto_start: true,
        log_level: 5,
        init: {
            'FilesAdded': function(up, files) {
				$('#fengmianModal2').modal('hide');
				loading.show(0,'图片上传中,请稍候...');
               console.info('fileadd');
            },
            'BeforeUpload': function(up, file) {
                var progress = new FileProgress(file, 'fsUploadProgress');
                var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                if (up.runtime === 'html5' && chunk_size) {
                    progress.setChunkProgess(chunk_size);
                }
            },
            'UploadProgress': function(up, file) {
                console.info(file.percent + "%");
            },
            'UploadComplete': function() {
				console.info('UploadComplete');
				setTimeout('qiniu_upload_update_db2()', 500);
            },
            'FileUploaded': function(up, file, info) {
                var progress = new FileProgress(file, 'fsUploadProgress');
                progress.setComplete(up, info);
				/******骏加的*******/
				var ext = Qiniu.getFileExtension(file.name);
				key = ext ? file.id + '.' + ext : file.id;
				file.target_name = key
				/******end*******/
				if(out_str!='') out_str += '|;|';
				out_str += file.name+'|,|'+file.target_name+'|,|'+file.origSize;
				console.info('FileUploaded');
            },
            'Error': function(up, err, errTip) {
                console.info(err);
            },
			//'Key': function(up, file){
			//	var key = Math.floor(Math.random()*10);
			//	return key;
			//}
                // ,
                // 'Key': function(up, file) {
                //     var key = "";
                //     // do something with key
                //     return key
                // }
        }
    });

    uploader.bind('FileUploaded', function() {
        console.log('hello man,a file is uploaded');
    });

});
