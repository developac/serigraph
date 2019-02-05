$( document ).ready(function() {

    // 1° TAB

    let combinationAttributes = new Array();
    let groups = new Array();

    $('.option').change(function() {

        if (typeof combinationAttributes[$(this).attr('data-attribute-group')] === "undefined"){
            combinationAttributes[$(this).attr('data-attribute-group')] = new Array();
        }

        let element = "<div style='width:15%;margin:10px' class='card col-sm-offset-1' id=card_"+$(this).attr('id')+">\n" +
            "  <div class=\"card-body\">\n" +
            "   "+$(this).attr('data-name')+"\n" +
            "  </div>\n" +
            "</div>";

        if($(this).is(":checked")) {
            $("#container").append(element);
            combinationAttributes[$(this).attr('data-attribute-group')].push($(this).attr('id'));
        }
        else{
            $('#card_'+$(this).attr('id')+'').remove();
            let index = combinationAttributes[$(this).attr('data-attribute-group')].indexOf($(this).attr('id'));
            if (index > -1) {
                combinationAttributes[$(this).attr('data-attribute-group')].splice(index, 1);
            }
        }
        //console.log(combinationAttributes);
        $(".option").val($(this).is(':checked'));

    });




    $('.checkGroup .group').on('change',function() {

        let id = parseInt($(this).val());

        if($(this).is(":checked")) {
            groups.push(id);
        }
        else{
            //let index = groups.indexOf($(this).attr('id'));
            let index = groups.indexOf(id);
            if (index > -1) {
                groups.splice(index, 1);
            }
        }
        //console.log(combinationAttributes);
        //$(".group").val($(this).is(':checked').val());

        console.log(ag_admin_url);

        $.ajax({
            type: 'POST',
            url: ag_admin_url + '&ajax&action=DoSomeAction',
            headers: {"cache-control": "no-cache"},
            dataType: 'json',
            async: false,
            data: {},
            error: function (response) {
                //errorFunction(response, sendData, elem);
                console.log(response);
            },
            success: function (response) {
                successFunction(response, sendData, elem);
                //console.log(response);
                return response;
            }
        });


    });

    $("#save").click(function (e) {

        e.preventDefault();
        e.stopPropagation();

        $("div").find(`[data-attr='save']`).find("div").css("display","block");

        let url = ajax_link;
        $.ajax({
            type: 'POST',
            url: url,
            cache: false,
            data: {
                method : 'save',
                ajax: true,
                product : $("input[name='product']").val(),
                group : $("label[for='group_"+$('.checkGroup input:checked').val()+"']").text().trim(),
                element : combinationAttributes,
                id_ag_group: $('input.group:checked').val()
            },
            success: function (result) {

                result = JSON.parse(result);

                let checkGroup = "<div class=\"form-check\">\n" +
                    "                                <input class=\"form-check-input group\" type=\"checkbox\" id=\""+result.id+"\">\n" +
                    "                                <label class=\"form-check-label\" for=\"\">\n" +
                    "                                    "+result.nome+"\n" +
                    "                                </label>\n" +
                    "                            </div>";

                $("div").find(`[data-attr='save']`).find("div").css("display","none");
                $("#container").html("");
                $('.option').prop('checked',false);
                //$('.checkGroup').append(checkGroup);
                combinationAttributes = new Array();
                groups = new Array();
                alert("Gruppo di combinazioni creato correttamente");
            },
            error:function (result){
                $("div").find(`[data-attr='save']`).find("div").css("display","none");
            }
        });


    });

    $("#generate").click(function (e) {

        e.preventDefault();
        e.stopPropagation();



        $("div").find(`[data-attr='generate']`).find("div").css("display","block");

        if (!groups.length) {
            $('.group:checked').each(function() {
                groups.push(this.value);
            }) ;
        }

        //console.log(groups);

        //return false;

        let url = ajax_link+"&method=generate&product="+$("input[name='product']").val();
        $.ajax({
            type: 'POST',
            url: url,
            cache: false,
            data: {
                method : 'generate',
                ajax: true,
                product : $("input[name='product']").val(),
                group : groups,
                id_ag_group: $('input.group:checked').val(),
            },
            success: function (result) {
                $("div").find(`[data-attr='generate']`).find("div").css("display","none");
                alert("Generazione avvenuta con successo");
            },
            error:function (result){
                $("div").find(`[data-attr='generate']`).find("div").css("display","none");
            }
        });


    });

    $("#export").click(function (e) {

        e.preventDefault();
        e.stopPropagation();

        let url = ajax_link+"&method=export&product="+$("input[name=\'product\']").val();
        document.location.href = url;

    });


    $("#import").click(function (e) {

        e.preventDefault();
        e.stopPropagation();

        $("div").find(`[data-attr='import']`).find("div").css("display","block");

        var file = $('#file_to_import')[0].files[0]
        var fd = new FormData();
        fd.append('file', file);
        fd.append('method', 'import');
        fd.append('ajax', '1');
        fd.append('product', $("input[name=\'product\']").val());

        let url = ajax_link;
        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            url: url,
            cache: false,
            data: fd,
            success: function (result) {
                result = JSON.parse(result);
                $("div").find(`[data-attr='import']`).find("div").css("display","none");
                if(result.error){
                    alert(result.error);
                }else{
                    alert("Importazione avvenuta con successo");
                }

            },
            error:function (result){
                $("div").find(`[data-attr='import']`).find("div").css("display","none");
            }
        });

    });

    $(".table-group").on("click",".delete_group",function (e) {

        let me = $(this);

        e.preventDefault();
        e.stopPropagation();

        if (confirm('Sei sicuro di voler proseguire con l\'eliminazione?')) {
            let url = ajax_link;
            $.ajax({
                type: 'POST',
                url: url,
                cache: false,
                data: {
                    method : 'deleteGroup',
                    ajax: true,
                    product : $("input[name='product']").val(),
                    idGroup : me.attr("data-attribute"),
                },
                success: function (result) {
                    me.closest("tr").remove();
                    alert("Operazione eseguita con successo");
                }
            });
        } else {
            // Do nothing!
        }


    });

    // 2° TAB

    let attributeTemp = new Array();
    let combinationAttributesTemp = new Array();

    $('#add').click(function(e) {

        e.preventDefault();
        e.stopPropagation();

        let element = "<div style='width:auto;margin:10px' class='card col-sm-offset-1' id=card2_"+$('select[name=optionTemp]').val()+">\n" +
            "  <div class=\"card-body\">\n" +
            "   "+$('select[name=optionTemp] option:selected').text()+" - "+$('input[name=text_value]').val()+""+$('select[name=type_value] option:selected').val()+"\n" +
            "  </div>\n" +
            "</div>";

        $("#container-2").append(element);

        let object = {
            'id_attribute_temp' : $('select[name=optionTemp]').val(),
            'value' : $('input[name=text_value]').val(),
            'type' : $('select[name=type_value] option:selected').val()
        }

        attributeTemp.push(object);

    });

    $('.option-2').change(function() {

        let element = "<div style='width:15%;margin:10px' class='card col-sm-offset-1' id=card_"+$(this).attr('id')+">\n" +
            "  <div class=\"card-body\">\n" +
            "   "+$(this).attr('data-name')+"\n" +
            "  </div>\n" +
            "</div>";

        if($(this).is(":checked")) {
            $("#container-2").append(element);
            combinationAttributesTemp.push($(this).attr('id'));
        }
        else{
            $('#card_'+$(this).attr('id')+'').remove();
            let index = combinationAttributesTemp.indexOf($(this).attr('id'));
            if (index > -1) {
                combinationAttributesTemp.splice(index, 1);
            }
        }
        //console.log(combinationAttributes);
        $(".option-2").val($(this).is(':checked'));

        console.log(combinationAttributesTemp);

    });

    $("#saveTemp").click(function (e) {

        e.preventDefault();
        e.stopPropagation();

        $("div").find(`[data-attr='saveTemp']`).find("div").css("display","block");

        let url = ajax_link;
        $.ajax({
            type: 'POST',
            url: url,
            cache: false,
            data: {
                method : 'saveTemp',
                ajax: true,
                product : $("input[name='product']").val(),
                attributeTemp : attributeTemp,
                element : combinationAttributesTemp
            },
            success: function (result) {

                result = JSON.parse(result);

                Object.keys(result.array).forEach(function(key){

                    let item = result.array[key];

                    let tr = "<tr>\n" +
                        "<td>"+item.temp+"</td>\n" +
                        "<td>"+item.comb+"</td>\n" +
                        "<td>"+item.value+"</td>\n" +
                        "<td><button class=\"delete_attribute_temp\" data-attribute-temp=\""+item.id+"\" data-attribute=\""+item.query+"\">Elimina</button></td>\n" +
                        "</tr>";

                    $(".table-temp tbody").append(tr);

                });

                $("#container-2").html("");
                $('.option-2').prop('checked',false);
                attributeTemp = new Array();
                combinationAttributesTemp = new Array();
                $("div").find(`[data-attr='saveTemp']`).find("div").css("display","none");
                alert("Operazione eseguita con successo");

            },
            error:function (result){
                $("div").find(`[data-attr='saveTemp']`).find("div").css("display","none");
            }
        });


    });

    $(".table-temp").on("click",".delete_attribute_temp",function (e) {

        let me = $(this);

        e.preventDefault();
        e.stopPropagation();

        if (confirm('Sei sicuro di voler proseguire con l\'eliminazione?')) {
            let url = ajax_link;
            $.ajax({
                type: 'POST',
                url: url,
                cache: false,
                data: {
                    method : 'deleteTemp',
                    ajax: true,
                    product : $("input[name='product']").val(),
                    idAttributeTemp : me.attr("data-attribute-temp"),
                    idsAttribute : me.attr("data-attribute"),
                },
                success: function (result) {
                    me.closest("tr").remove();
                    alert("Operazione eseguita con successo");
                }
            });
        } else {
            // Do nothing!
        }


    });


    //TAB 3°

    let combinationAttributesRule = new Array();
    let rules = new Array();

    $("#accordionModify").on("change",".option3",function (e) {

        let element = "<div style='width:15%;margin:10px' class='card col-sm-offset-1' id=card_"+$(this).attr('id')+">\n" +
            "  <div class=\"card-body\">\n" +
            "   "+$(this).attr('data-name')+"\n" +
            "  </div>\n" +
            "</div>";

        if($(this).is(":checked")) {
            $("#container-3").append(element);
            combinationAttributesRule.push($(this).attr('id'));
        }
        else{
            $('#card_'+$(this).attr('id')+'').remove();
            let index = combinationAttributesTemp.indexOf($(this).attr('id'));
            if (index > -1) {
                combinationAttributesRule.splice(index, 1);
            }
        }
        //console.log(combinationAttributes);
        $(".option3").val($(this).is(':checked'));

        //console.log(combinationAttributesTemp);

    });

     $('#addRule').click(function(e) {

        e.preventDefault();
        e.stopPropagation();

        let element = "<div style='width:auto;margin:10px' class='card col-sm-offset-1' id=card2_"+$('select[name=select-group]').val()+">\n" +
            "  <div class=\"card-body\">\n" +
            "   "+$('input[name=text_rule]').val()+" - "+$('select[name=select-group] option:selected').text()+" - "+$('input[name=text_value_rule]').val()+""+$('select[name=type_value_rule] option:selected').val()+"\n" +
            "  </div>\n" +
            "</div>";

        $("#container-3").append(element);

        let object = {
            'id_group' : $('select[name="select-group"] :selected').val(),
            'value' : $('input[name=text_value_rule]').val(),
            'type' : $('select[name=type_value_rule] option:selected').val(),
            'rule' : $('input[name=text_rule]').val()
        }

        console.log(object);

        rules.push(object);

    });



    $("#saveRule").click(function (e) {

        e.preventDefault();
        e.stopPropagation();

        $("div").find(`[data-attr='saveRule']`).find("div").css("display","block");

        let url = ajax_link;
        $.ajax({
            type: 'POST',
            url: url,
            cache: false,
            data: {
                method : 'saveRule',
                ajax: true,
                product : $("input[name='product']").val(),
                rule : rules,
                element : combinationAttributesRule,
            },
            success: function (result) {

                result = JSON.parse(result);

                // Object.keys(result.array).forEach(function(key){
                //
                //     let item = result.array[key];
                //
                //     let tr = "<tr>\n" +
                //         "<td>"+item.temp+"</td>\n" +
                //         "<td>"+item.comb+"</td>\n" +
                //         "<td>"+item.value+"</td>\n" +
                //         "<td><button class=\"delete_attribute_temp\" data-attribute-temp=\""+item.id+"\" data-attribute=\""+item.query+"\">Elimina</button></td>\n" +
                //         "</tr>";
                //
                //     $(".table-temp tbody").append(tr);
                //
                // });

                $("#container-3").html("");
                $('.option3').prop('checked',false);
                rules = new Array();
                combinationAttributesRule = new Array();
                $("div").find(`[data-attr='saveRule']`).find("div").css("display","none");
                alert("Operazione eseguita con successo");

            },
            error:function (result){
                $("div").find(`[data-attr='saveTemp']`).find("div").css("display","none");
            }
        });


    });


    $('select[name="select-group"]').change(function(e) {

        e.preventDefault();
        e.stopPropagation();

        let me = $(this);


        let url = ajax_link;
        $.ajax({
            type: 'POST',
            url: url,
            cache: false,
            data: {
                method : 'getAttributeGroup',
                ajax: true,
                product : $("input[name='product']").val(),
                group : me.val()
            },
            success: function (result) {

                result = JSON.parse(result);

                $("#accordionModify").html(result.html);

                $("#container-3").html("");
                $('.option3').prop('checked',false);

            },
            error:function (result){

            }
        });


    });


    $(".table-rule").on("click",".delete_attribute_rule",function (e) {

        let me = $(this);

        e.preventDefault();
        e.stopPropagation();

        if (confirm('Sei sicuro di voler proseguire con l\'eliminazione?')) {
            let url = ajax_link;
            $.ajax({
                type: 'POST',
                url: url,
                cache: false,
                data: {
                    method : 'deleteRule',
                    ajax: true,
                    product : $("input[name='product']").val(),
                    idRule : me.attr("data-attribute-id")
                },
                success: function (result) {
                    me.closest("tr").remove();
                    alert("Operazione eseguita con successo");
                }
            });
        } else {
            // Do nothing!
        }


    });



});