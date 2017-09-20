var dfid = 0;

$(function() {
    do_init();
});

function do_init() {
    var uploader = Qiniu.uploader({
        filters: [
            { title: "Image files", extensions: "jpg,jpeg" }
        ],
        runtimes: 'html5,flash,html4',
        browse_button: 'pickfiles',
        container: 'upload_container',
        drop_element: 'upload_container',
        max_file_size: '100mb',
        flash_swf_url: 'plupload/Moxie.swf',
        dragdrop: false,
        chunk_size: '4mb',
        multi_selection: !(mOxie.Env.OS.toLowerCase() === "ios"),
        uptoken_url: $('#uptoken_url').val(),
        domain: $('#domain').val(),
        get_new_uptoken: false,
        unique_names: true,
        auto_start: true,
        log_level: 5,
        init: {
            'FilesAdded': function(up, files) {
                for (var i in files) {
                    previewImage(files[i], appendProductImage);
                }
            },
            'BeforeUpload': function(up, file) {
                // 每个文件上传前，处理相关的事情
                $('#img_' + file.id + ' span:first').text('上传中 0%');
            },
            'UploadProgress': function(up, file) {
                // 每个文件上传时，处理相关的事情
                $('#img_' + file.id + ' span:first').text('上传中 ' + file.percent + '%');
                $('#img_' + file.id + ' .upload-loading-percentage').width(file.percent + '%');
            },
            'FileUploaded': function(up, file, info) {
                var ext = Qiniu.getFileExtension(file.name);
                key = ext ? file.id + '.' + ext : file.id;
                file.target_name = key;
                var out_str = file.name + '|,|' + file.target_name + '|,|' + file.origSize;

                //update data
                $.post(upload_save_img_url, { udata: out_str, fid: dfid }, function(data) {
                        if (data.length > 0) {
                            //select - able , add id area

                            $('#img_' + file.id + ' span:first').text('上传完成');
                            $('#img_' + file.id + ' .img-operate').show();
                            $('#product_img_'+file.id).val(data[0]);
                        } else {
                            $('#img_' + file.id + ' span:first').text('上传失败');
                            $('#img_' + file.id + ' .upload-loading-percentage').css({'background-color':'red'});
                        }
                    }, "json");
            },
            'Error': function(up, err, errTip) {
                //上传出错时，处理相关的事情
                if(-600==err.code){
                    alert('图片不能超过20M！');
                }
            },
            'UploadComplete': function() {
                //队列文件处理完毕后，处理相关的事情
            },
            'Key': function(up, file) {
                // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                // 该配置必须要在unique_names: false，save_key: false时才生效
                var key = "";
                // do something with key here
                return key
            }
        }
    });
    // domain为七牛空间对应的域名，选择某个空间后，可通过 空间设置->基本设置->域名设置 查看获取
    // uploader为一个plupload对象，继承了所有plupload的方法
}

//append image node to dom;   
//productId !==''  ==> uploaded file
function appendProductImage(imgsrc, fileid, filename, productId) {
    fileid = fileid.replace('.','_');
    var _isUploadedFile = (''!==productId);
    var _html = '<div class="upload-image" id="img_' + fileid + '" onclick="onSelectImage(this)" ';
        _html += 'style="background-image:url(' + imgsrc + ');background-position:center; background-size: cover; cursor:pointer;">';
        _html += '<div class="img-operate" '+ (_isUploadedFile ? "style=\"display:block\"":"")+'>';
        _html += '<div class="checkbox-state">';
        _html += '<img src="/static/images/ico_select.png" style="width: 16px; height: 14px; margin: 8px 8px;" />';
        _html += '</div>';
        _html += '<img src="/static/images/ico_del.png" class="remove-img" onclick="delImage(this,\'product_img_'+fileid+'\')" />';
        _html += '</div>';
        _html += '<div class="upload-loading">';
        _html += '<span>'+ (_isUploadedFile ? "已上传":"等待中") +'</span>';
        _html += '<div class="upload-loading-percentage" '+ (_isUploadedFile ? "style=\"width:100%\"":"")+'></div>';
        _html += '</div>';
        _html += '<span class="center" title="'+ filename +'">' + filename + '</span>';
        _html += '<input type="hidden" id="product_img_'+fileid +'" value="'+productId+'"/>';
        _html += '</div>';
    $('#upload_container').append(_html);
}

//do preiview before upload
function previewImage(file, callback) {
    if (!file || !/image\//.test(file.type)) return;
    if (file.type == 'image/gif') {
        var fr = new mOxie.FileReader();
        fr.onload = function() {
            callback(fr.result, file.id, file.name, '');
            fr.destroy();
            fr = null;
        }
        fr.readAsDataURL(file.getSource());
    } else {
        var preloader = new mOxie.Image();
        preloader.onload = function() {
            preloader.downsize(300, 300); //先压缩一下要预览的图片,宽300，高300
            var imgsrc = preloader.type == 'image/jpeg' ? preloader.getAsDataURL('image/jpeg', 80) : preloader.getAsDataURL(); //得到图片src,实质为一个base64编码的数据
            callback && callback(imgsrc, file.id, file.name, ''); //callback传入的参数为预览图片的url
            preloader.destroy();
            preloader = null;
        };
        preloader.load(file.getSource());
    }
}

function delImage(e, fileidField){
    event.stopPropagation();
    var _val = parseInt($('#'+fileidField).val());
    var _index = edit_select_images.indexOf(_val);
    if(_index>-1){
        edit_select_images.splice(_index, 1);
    }
    $(e).parents('.upload-image').remove();
    //删除远程图片
    $.post("/user/img_del?ids="+_val,null,null);
}
