<script src="assets/dist/js/jquery-3.1.1.min.js"></script>
   <script src="assets/select2/select2.min.js"></script>
   <script src="assets/dropify/dist/js/dropify.min.js"></script>
    <script src="assets/dist/js/bootstrap.min.js"></script>
    <script>
        
        $(document).ready(function() {
    $('.select2').select2();
    
    $('.dropify').dropify();

        // Translated
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove: 'Supprimer',
                error: 'Désolé, le fichier trop volumineux'
            }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify');
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });
        
        function formProcessor(form_id,submit_btn,notification,return_text,url,callback_selector,mass_load_selector,val){
     $(form_id).submit(function(e){
            e.preventDefault(); //prevent default action 
               var data = new FormData(this);
                var btn = $(submit_btn);
                var info = $(notification);
                var load_body = $(callback_selector);
                var mass_loader = $(mass_load_selector);
                 
                        btn.html("<span style='color:#fff'>wait </span>");
                        info.html("<span style='color:#fff'>wai </span>");
                        //load_body.html("<span style='color:#fff'>"+loader_gif.SMALL+" </span>");
                        $.ajax({ //ajax form submit
                                type: "POST",
                                data: data,
                                url:url,
                                dataType:"json",
                                cache:false,
                                contentType: false,
                                processData:false
                        }).done(function(res){ //fetch server "json" messages when done
                                if(res.type === "error"){
                                     $(notification).html("<div class=\"error\"> "+res.text+"</div>");
                                     btn.html(return_text);
                                     btn.attr("disabled",false);
                                     //load_body.html(res.content);
                                     //$(notification).html("<div class=\"error\">Error!! "+res.text+"</div>");
                                     //info.delay(10000).fadeOut("fast");
                                    //info.html(res.text).delay(10000).fadeOut("fast");
                                    return false;
                                    e.stopPropagation();
                                    //redirect(val);
                                }
                                
                                if(res.type === "done"){
                                    
                                    info.html("<div class=\"success text-center\">"+res.text+"</div>");
                                    //info.html("<div class=\"success\">"+res.text+"</div>").delay(10000).fadeOut("fast");
                                    btn.attr("disabled",false);
                                    btn.html(return_text);
                                    load_body.html(res.content);
                                    mass_loader.html(res.mass_load);
                                    //loop(res);
                                    //redirect(val);
                                    e.stopImmediatePropagation();
                                   return false;
                                   
                                }
                        });
                    });
      
}

function loginProcessor(form_id,login_btn,notification,url,val){
  $(form_id).submit(function(e){
            e.preventDefault(); //prevent default action 
               var data = $(this).serialize();
                var btn = $(login_btn);
                var info = $(notification);

                 $.ajaxSetup(
                        {
                                beforeSend: function()
                                {
                                        btn.attr("disabled",true);
                                        btn.html("<span style='color:#fff'>Authenticating... </span>");
                                },
                                complete: function()
                                {
                                        btn.attr("disabled",false);
                                        btn.html( "Login!");
                                }
                        });
                       
                        $.ajax({ //ajax form submit
                                type: "POST",
                                data: data,
                                url:url,
                                dataType:"json",
                                cache:false
                        }).done(function(res){ //fetch server "json" messages when done
                                if(res.type === "error"){
                                     $(login_btn).html('Login');
                                      $(info).html("<div class=\"error\">"+res.text+"</div>");
                                }
                                if(res.type === "done"){
                                    $(info).html("<div class=\"success\">!!Success Please Wait.</div>");
                                     $(login_btn).html('Login');
                                     //redirect(val);
                                     window.setTimeout(function(){
                                     window.location.href = res.text;     
                                     }, 3000);
                                }
                        });
                    });
                } 
                
       function formProcessorWithFileCallbacks(form_id,submit_btn,notification,return_text,url,callback_selector,val){
  $(form_id).submit(function(e){
            e.preventDefault(); //prevent default action 
               var data = new FormData(this);
                var btn = $(submit_btn);
                var info = $(notification);
                var load_body = $(callback_selector);
                 
                        btn.html("<span style='color:#fff'>pls wait </span>");
                        $.ajax({ //ajax form submit
                                type: "POST",
                                data: data,
                                url:url,
                                dataType:"json",
                                cache:false,
                                contentType: false,
                                processData:false
                        }).done(function(res){ //fetch server "json" messages when done
                                if(res.type === "error"){
                                     $(notification).html("<div class=\"error text-center\">Error!! "+res.text+"</div>");
                                     btn.html(return_text);
                                     btn.attr("disabled",false);
                                     window.setTimeout(function(){
                                     $(notification).html("<div class=\"error\"></div>");
                                     }, 5000);
                                    e.stopPropagation();
                                    return false;
                                    
                                    
                                }
                                if(res.type === "done"){
                                    info.html("<div class=\"success text-center\">"+res.text+"</div>");
                                    btn.attr("disabled",false);
                                    btn.html(return_text);
                                    load_body.html(res.content);
                                    window.setTimeout(function(){
                                     $(notification).html("<div class=\"done\"></div>");
                                     }, 5000);
                                    //redirect(val);
                                    e.stopImmediatePropagation();
                                   return false;
                                   
                                }
                        });
                    });
                }           
        </script>
  </body>

<!-- Mirrored from getbootstrap.com/docs/4.0/examples/album/ by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 30 Jan 2018 15:12:46 GMT -->
</html>