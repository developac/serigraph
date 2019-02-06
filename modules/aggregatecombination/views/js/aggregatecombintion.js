$(document).ready(function() {
    ag.init();
})

var ag = {
    combinationAttributes: Array(),
    groups: Array(),
    ajax: {
        _request: function (sendData, successFunction, errorFunction, elem) {

            elem = elem || null;
            errorFunction = errorFunction || function (response) {
            };
            successFunction = successFunction || function (response) {
            };

            sendData.ajax = true;
            //sendData.controller = 'AdminAggregateCombination';

            $.ajax({
                type: 'POST',
                url: ag_admin_url+"&ajax&action="+sendData.action,
                headers: {"cache-control": "no-cache"},
                dataType: 'json',
                async: true,
                data: sendData,
                beforeSend: function() {

                },
                error: function (response) {
                    console.log('error', response);
                    //errorFunction(response, sendData, elem);
                },
                success: function (response) {
                    console.log('success', response);
                    //successFunction(response, sendData, elem);
                    //return response;
                },
                complete: function (response) {
                    console.log('complete', response);
                }
            });
        }
    },
    events: {
        doClick: function (selector, innerFunction) {
            $(document).on('click', selector, function (e) {
                innerFunction($(this));
                e.preventDefault();
            })
        },
        doChange: function (selector, innerFunction) {
            $(document).on('change, click', selector, function (e) {
                innerFunction($(this));
                //e.preventDefault();
            })
        },
        changeOption: function (e) {
            if (typeof ag.combinationAttributes[$(this).attr('data-attribute-group')] === "undefined"){
                ag.combinationAttributes[$(this).attr('data-attribute-group')] = new Array();
            }

            let element = "<div style='width:15%;margin:10px' class='card col-sm-offset-1' id=card_"+$(this).attr('id')+">\n" +
                "  <div class=\"card-body\">\n" +
                "   "+$(this).attr('data-name')+"\n" +
                "  </div>\n" +
                "</div>";

            if($(this).is(":checked")) {
                $("#container").append(element);
                ag.combinationAttributes[$(this).attr('data-attribute-group')].push($(this).attr('id'));
            }
            else{
                $('#card_'+$(this).attr('id')+'').remove();
                let index = ag.combinationAttributes[$(this).attr('data-attribute-group')].indexOf($(this).attr('id'));
                if (index > -1) {
                    ag.combinationAttributes[$(this).attr('data-attribute-group')].splice(index, 1);
                }
            }
            //console.log(combinationAttributes);
            $(".option").val($(this).is(':checked'));
        },
        changeGroup: function (e) {
            let id = parseInt($(this).val());

            if($(this).is(":checked")) {
                ag.groups.push(id);
            }
            else{
                //let index = groups.indexOf($(this).attr('id'));
                let index = ag.groups.indexOf(id);
                if (index > -1) {
                    ag.groups.splice(index, 1);
                }
            }
        },
        saveNewGroup: function () {
            $("div").find(`[data-attr='save']`).find("div").css("display","block");

            data = {
                action : 'SaveGroup',
                product : $("input[name='product']").val(),
                group : $("label[for='group_"+$('.checkGroup input:checked').val()+"']").text().trim(),
                element : ag.combinationAttributes,
                id_ag_group: $('input.group:checked').val()
            };

            ag.ajax._request(data, ag.callbaks.saveSuccess, ag.callbaks.saveError, null);
        },
        generateCombinations: function() {
            $("div").find(`[data-attr='generate']`).find("div").css("display","block");

            if (!ag.groups.length) {
                $('.group:checked').each(function() {
                    ag.groups.push(this.value);
                }) ;
            }
            data = {
                action : 'GenerateCombinations',
                ajax: true,
                product : $("input[name='product']").val(),
                group : ag.groups,
                id_ag_group: $('input.group:checked').val(),
            };

            ag.ajax._request(data, ag.callbaks.generateSuccess, ag.callbaks.generateError, null);

        },
        init: function () {
            ag.events.doChange('.option', this.changeOption);
            ag.events.doChange('.checkGroup .group', this.changeGroup);
            ag.events.doClick('#saveGroup', this.saveNewGroup);
            ag.events.doClick('#generateCombinations', this.generateCombinations);
        }
    },
    callbaks: {
        saveSuccess: function () {
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
        saveError: function () {
            $("div").find(`[data-attr='save']`).find("div").css("display","none");
        },
        generateSuccess: function () {
            $("div").find(`[data-attr='generate']`).find("div").css("display","none");
            alert("Generazione avvenuta con successo");
        },
        generateError: function () {
            $("div").find(`[data-attr='generate']`).find("div").css("display","none");
        }
    },
    init: function() {
        this.events.init();
    }
}