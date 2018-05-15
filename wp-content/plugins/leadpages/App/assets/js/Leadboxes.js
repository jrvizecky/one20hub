(function ($) {

    $(function () {


        var $body = $('body');

        function init() {
            hideScriptBoxes();
            timedLeadBoxes();
            exitLeadBoxes();
            setPostTypes();
            $('#leadboxesLoading').hide();
            $('#timedLoading').hide();
            $('#exitLoading').hide();
            $('.ui-loading').hide();
            $("#leadboxesForm").show();
        }

        init();


        $body.on('change', '#leadboxesTime', function () {
            console.log($(this).val());
            if($(this).val() == 'none'){
                $('#selectedLeadboxSettings').hide();
            }

            if($(this).val() == 'ddbox'){
                showTimedScriptBox();
            }else{
                hideTimedScriptBox();
            }

            populateTimedStats(this);

        });

        if($("#leadboxesTime").val() != 'none'){
            populateTimedStats($("#leadboxesTime"));
        }
        if($("#leadboxesExit").val() != 'none'){
            populateExitStats($("#leadboxesExit"));
        }

        $body.on('change', '#leadboxesExit', function () {
            if($(this).val() == 'none'){
                $('#selectedExitLeadboxSettings').hide();
            }

            if($(this).val() == 'ddbox'){
                showExitScriptBox();
            }else{
                hideExitScriptBox();
            }
            populateExitStats(this);

        });

        $body.on('click', '#timedLeadboxRefresh', function(){
            $('#timedLoading').css('display', 'inline');
            $.ajax({
                type : "GET",
                url : leadboxes_object.ajax_url,
                data : {
                    action: "allLeadboxesAjax"
                },
                success: function(response) {
                    $('ui-loading').hide();
                    var leadboxes = $.parseJSON(response);
                    $('.timeLeadBoxes').html(leadboxes.timedLeadboxes);
                }
            });

        });

        $body.on('click', '#exitLeadboxRefresh', function(){
            $('#exitLoading').css('display', 'inline');
            $.ajax({
                type : "GET",
                url : leadboxes_object.ajax_url,
                data : {
                    action: "allLeadboxesAjax"
                },
                success: function(response) {
                    $('#exitLoading').hide();
                    var leadboxes = $.parseJSON(response);
                    $('.exitLeadBoxes').html(leadboxes.exitLeadboxes);
                }
            });

        });

        function hideScriptBoxes(){
            $body.find('.timedLeadboxScript').css('display', 'none');
            $body.find('.exitLeadboxScript').css('display', 'none');
        }

        function showTimedScriptBox(){
            var timedDropdownValue = $body.find('#leadboxesTime').val();
            if(timedDropdownValue == 'ddbox'){
                $body.find('.timedLeadboxScript').css('display', 'flex');
                $body.find('#selectedLeadboxSettings').css('display', 'none');
            }
        }

        function hideTimedScriptBox(){
            var exitDropdownValue = $body.find('#leadboxesTime').val();
            if(exitDropdownValue != 'ddbox'){
                $body.find('.timedLeadboxScript').css('display', 'none');
                $body.find('#selectedLeadboxSettings').css('display', 'block');
            }
        }

        function showExitScriptBox(){
            var exitDropdownValue = $body.find('#leadboxesExit').val();
            if(exitDropdownValue == 'ddbox'){
                $body.find('.exitLeadboxScript').css('display', 'flex');
                $body.find('#selectedExitLeadboxSettings').css('display', 'none');
            }
        }

        function hideExitScriptBox(){
            var timedDropdownValue = $body.find('#leadboxesExit').val();
            if(timedDropdownValue != 'ddbox'){
                $body.find('.exitLeadboxScript').css('display', 'none');
                $body.find('#selectedExitLeadboxSettings').css('display', 'block');
            }
        }

        function populateTimedStats($this) {
            var timeTillAppear = $($this).find(':selected').data('timeappear');
            var pageView = $($this).find(':selected').data('pageview');
            var daysTillAppear = $($this).find(':selected').data('daysappear');

            var stats = '<ul class="leadbox-stats">'+
                stat_row("Time before it appears: ", timeTillAppear + ' seconds') +
                stat_row("Page views before it appears: ", pageView + ' views') +
                stat_row("Don't reshow for the next: ", daysTillAppear + ' days') +
                    '</ul>';
            $("#selectedLeadboxSettings").html(stats);
        }

        function populateExitStats($this) {
            var daysTillAppear = $($this).find(':selected').data('daysappear');
            var stats ='<ul class="leadbox-stats">'+
                stat_row("Don't reshow for the next ", daysTillAppear + ' days')+
                '</ul>';
            $("#selectedExitLeadboxSettings").html(stats);
        }

        function stat_row(label, value) {
            return '<li>'+ label + value+'</li>';

        }

        function timedLeadBoxes() {
            $('.timeLeadBoxes').html(leadboxes_object.timedLeadboxes);
            showTimedScriptBox();
        }

        function exitLeadBoxes() {
            $('.exitLeadBoxes').html(leadboxes_object.exitLeadboxes);
            showExitScriptBox();
        }

        function setPostTypes() {
            $('.postTypesForTimedLeadbox').html(leadboxes_object.postTypesForTimedLeadboxes);
            $('.postTypesForExitLeadbox').html(leadboxes_object.postTypesForExitLeadboxes);
            $('.postTypesForExitLeadbox').html(leadboxes_object.postTypesForExitLeadboxes);
        }

    });

}(jQuery));