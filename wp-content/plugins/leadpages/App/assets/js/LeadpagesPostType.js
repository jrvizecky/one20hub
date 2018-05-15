(function ($) {

    $(document).ready(function() {

        function getLeadPages(clear_cache = false){
            var start = new Date().getTime();
            var action = 'get_pages_dropdown' + (clear_cache ? '_nocache' : '');

            $.ajax({
                type: 'POST',
                url: ajax_object.ajax_url,
                data: {
                    action: action,
                    id: ajax_object.id
                },
                beforeSend: function (data) {
                  $(".ui-loading").show();
                },
                success: function (response) {
                    var end = new Date().getTime();
                    console.log('milliseconds passed', end - start);
                    var pageType = $('input[name=leadpages-post-type]:checked').val();
                    if(pageType === 'nf' || pageType === 'fp'){
                        $("#leadpage-slug").hide();
                    }else{
                        $("#leadpage-slug").show();
                    }

                    $(".ui-loading").hide();
                    $("#leadpageType").show();
                    $(".leadpagesSelect").show();
                    $("#leadpages_my_selected_page").append(response);
                },

                complete: function(response) {
                    var elem = $(response.responseText);

                    $("#leadpages_my_selected_page").trigger('change');

                    //setup select 2 on the leadpages dropdown(sets up searchbox etc)
                    $(".leadpage_select_dropdown").select2({
                      templateResult: function (item) {
                        if (!item.element) return;

                        var data = $(item.element).data();
                        var stats = data.published + ' &bull; ';

                        if (data.issplit) {
                            stats += 'Split Test';

                        } else {
                            stats += data.views + ' views &bull; '
                                   + data.optins + ' optins</small>';
                        }

                        return $(
                            '<div>'
                            + '<div>' + item.text + '</div>'
                            + '<small style="color: #bbb">'
                            + stats + '</div>');
                        },
                        placeholder: "Select a Leadpage",
                        allowClear: true
                    });

                    $('.sync-leadpages').show();
                }
            });
        }

        getLeadPages();

        $("#leadpages_my_selected_page").on("select2:open", function() {
          $(".select2-search__field").attr("placeholder", "Search Your Leadpages");
        });

        var $body = $('body');

        function hideSlugFor404andHome(){
            var pageType = $('input[name=leadpages-post-type]:checked').val()
            if(pageType === 'nf' || pageType === 'fp'){
                $("#leadpage-slug").hide();
            }else{
                $("#leadpage-slug").show();
            }
        }

        $body.on('change', '#leadpages_my_selected_page', function(){
            var item = $("option:selected", this);
            var selected_page_name = item.text();
            var isEdit = $(".leadpages-edit-wrapper").data('isEdit');
            $("#leadpages_name").val(selected_page_name);
            if (isEdit != undefined && isEdit != true) {
                $('.leadpages_slug_input').val(item.data('slug'));
            }
        });

        $body.on('change', 'input[name=leadpages-post-type]', function(){
            var pageType = $("#leadpageType").val();
            hideSlugFor404andHome();
            if(pageType === 'fp' || pageType === 'nf'){
                $(".leadpage_slug_error").remove();
            }
        });

        //hide preview button for Leadpages
        $("#preview-action").hide();

        //refresh button for leadpages
        $body.on('click', '.sync-leadpages', function (e) {
          //show loading icons
          $('.sync-leadpages i').hide();

          //remove all old data
          $('#leadpages_my_selected_page').empty();

          //get new leadpages and recreate dropdown
          getLeadPages(true);

          $('.sync-leadpages i').show();
        });

        $body.on('click', '#publish', function (e) {

            $("#publishing-action .spinner").removeClass('is-active');
            $("#publish").removeClass('disabled');
            var error = false;
            $(".leadpages_error").remove();
            $('#leadpages_my_selected_page').css('border-color', '#ddd');
            $('#leadpageType').css('border-color', '#ddd');
            $leadpageType = $("#leadpageType").val();
            $selectedPage = $("#leadpages_my_selected_page").val();
            $leadpageSlug = $('.leadpages_slug_input').val();

            if($leadpageType == 'none'){
                e.preventDefault();
                $( ".wrap h1" ).after( "<div class='error notice leadpages_error'><p>Please select a page type</p></div>" );
                $('#leadpageType').css('border-color', 'red');
                error = true;
            }

            if($selectedPage == 'none'){
                e.preventDefault();
                $( ".wrap h1" ).after( "<div class='error notice leadpages_error'><p>Please select a Leadpage</p></div>" );
                $('#leadpages_my_selected_page').css('border-color', 'red');
                error = true;
            }

            if($leadpageType !== 'fp' && $leadpageType !== 'nf'){

                if($leadpageSlug.length === 0){
                    e.preventDefault();
                    $(".wrap h1").after("<div class='error notice leadpages_error leadpage_slug_error'><p>Slug appears to be empty. Please add a slug.</p></div>");
                    $('.leadpages_slug_input').css('border-color', 'red');
                    error = true;
                }
            }

            if($leadpageType === 'fp' || $leadpageType === 'nf'){
                $(".leadpage_slug_error").remove();
            }

            if(error){
                return;
            }
        });

        //remove all the unneeded styling from metaboxes
        function removeMetaBoxExpand(){
            $('.postbox .hndle').unbind('click.postboxes');
            $('.postbox .handlediv').remove();
            $('.postbox').removeClass('closed');
            $('.postbox .hndle').remove();
        }
        removeMetaBoxExpand();

        //setting up the Leadpages Post Type Page for redesign
        $("#leadpage-create").removeClass('postbox');
        $("#leadpage-create > div").removeClass('inside');

    });
}(jQuery));
