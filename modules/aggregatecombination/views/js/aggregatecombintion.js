$(document).ready(function() {
    ag.init();
})

var ag = {
    combinationAttributes: [],
    groups: [],
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
                    errorFunction(response, elem);
                },
                success: function (response) {
                    console.log('success', response);
                    successFunction(response, elem);
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


            if (typeof ag.combinationAttributes[$(e).attr('data-attribute-group')] === "undefined"){
                ag.combinationAttributes[$(e).attr('data-attribute-group')] = new Array();
            }

            let element = "<div style='width:15%;margin:10px' class='card col-sm-offset-1' id=card_"+$(e).attr('id')+">\n" +
                "  <div class=\"card-body\">\n" +
                "   "+$(e).attr('data-name')+"\n" +
                "  </div>\n" +
                "</div>";

            if($(e).is(":checked")) {
                $("#container").append(element);
                ag.combinationAttributes[$(e).attr('data-attribute-group')].push($(e).attr('id'));
            }
            else{
                $('#card_'+$(e).attr('id')+'').remove();
                let index = ag.combinationAttributes[$(e).attr('data-attribute-group')].indexOf($(e).attr('id'));
                if (index > -1) {
                    ag.combinationAttributes[$(e).attr('data-attribute-group')].splice(index, 1);
                }
            }

            ag.combinationAttributes = ag.combinationAttributes.filter(function(x){
                return (x !== (undefined || null || ''));
            });

            //$(".option").val($(e).is(':checked'));
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

            console.log(ag.combinationAttributes);

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
        deleteGroup: function(element) {
            if (confirm("Sei sicuro di voler proseguire con l'eliminazione?")) {
                let me = $(element);
                data = {
                    action : 'DeleteGroup',
                    ajax: true,
                    product : $("input[name='product']").val(),
                    idGroup : me.attr("data-attribute"),
                };

                ag.ajax._request(data, ag.callbaks.deleteSucces, ag.callbaks.deleteError, null);
            }
        },
        init: function () {

            ag.combinationAttributes = [];
            ag.groups = [];

            ag.events.doChange('.option', this.changeOption);
            ag.events.doChange('.checkGroup .group', this.changeGroup);
            ag.events.doClick('#saveGroup', this.saveNewGroup);
            ag.events.doClick('#generateCombinations', this.generateCombinations);
            ag.events.doClick('.table-group .delete_group', this.deleteGroup);


        }
    },
    callbaks: {
        saveSuccess: function (result, element) {
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
        },
        deleteSucces: function (response, element) {
            var $tr = $(element).closest('tr');

            if (response.status == true) {

                console.log($tr);

            }
        },
        deleteError: function () {

        }
    },
    init: function() {
        this.events.init();
    }
}