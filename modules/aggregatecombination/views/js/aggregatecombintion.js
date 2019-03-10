$(document).ready(function() {
    ag.init();
})

var ag = {
    combinationAttributes: [],
    groups: [],
    rules: [],
    attributesToRemove: [],
    combinationAttributesRule: [],
    combinationAttributesTemp: [],
    ajax: {
        _request: function (sendData, successFunction, errorFunction, elem, callback) {

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

                    if(typeof callback == 'function') {
                        callback(response);
                    }
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
                e.stopImmediatePropagation();
            })
        },
        doChange: function (selector, innerFunction) {
            $(document).on('change, click', selector, function (e) {
                innerFunction($(this));
                //e.preventDefault();
                //e.stopPropagation();
            })
        },
        changeOption: function (e) {
            if (typeof ag.combinationAttributes[$(e).attr('data-attribute-group')] === "undefined"){
                ag.combinationAttributes[$(e).attr('data-attribute-group')] = new Array();
            }

            if (typeof ag.attributesToRemove[$(e).attr('data-attribute-group')] === "undefined"){
                ag.attributesToRemove[$(e).attr('data-attribute-group')] = new Array();
            }

            var pos = $('#sectionA #container .visual-group').length;

            let elementParent = "<div class='visual-group' data-attribute-group='"+$(e).attr('data-attribute-group')+"' data-position='"+pos+"'><div class='handler'><i class='fa fa-arrows'></i></div><div class='group-name'>"+$(e).closest('.card').attr('data-group-name')+" <a href='javascript: void(0);' class='toggler'><i class='fa fa-plus-circle'></i></a></div><div class='content'></div></div>"


            let element = "<div style='margin:10px' class='card' id=card_"+$(e).attr('id')+" data-position='0' data-id-attribute='"+$(e).attr('id')+"'>\n" +
                "  <div class=\"card-body\">\n" +
                "  <div class=\"handler\"><i class='fa fa-arrows'></i></div>\n" +
                "  <div class=\"value-name\">"+$(e).attr('data-name')+"</div>\n" +
                "  </div>\n" +
                "</div>";

            if($(e).is(":checked")) {

                if (!$('.visual-group[data-attribute-group="'+$(e).attr('data-attribute-group')+'"]').length)
                    $('#container').append(elementParent);

                $('.visual-group[data-attribute-group="'+$(e).attr('data-attribute-group')+'"] .content').append(element);
                ag.combinationAttributes[$(e).attr('data-attribute-group')].push($(e).attr('id'));

                ag.attributesToRemove[$(e).attr('data-attribute-group')].push($(e).attr('id'));
                let index = ag.attributesToRemove[$(e).attr('data-attribute-group')].indexOf($(e).attr('id'));
                if (index > -1) {
                    ag.attributesToRemove[$(e).attr('data-attribute-group')].splice(index, 1);
                }
            }
            else{
                $('#card_'+$(e).attr('id')+'').remove();
                let index = ag.combinationAttributes[$(e).attr('data-attribute-group')].indexOf($(e).attr('id'));
                if (index > -1) {
                    ag.combinationAttributes[$(e).attr('data-attribute-group')].splice(index, 1);
                }
                ag.attributesToRemove[$(e).attr('data-attribute-group')].push($(e).attr('id'));
            }

            $(".option").val($(e).is(':checked'));

            $('#sectionA #container').sortable({
                items: " > .visual-group",
                connectWith: $(this),
                handle: '> .handler',
                cancel: '> .toggler',
                revert: true,
                placeholder: 'ui-state-highlight',
                tolerance: 'intersect',
                opacity: 0.6,
                cursor: "pointer",
                containment: "parent",
                forcePlaceholderSize: true,
                start: function(event, ui) {
                    ui.placeholder.height(ui.helper[0].offsetHeight);
                    position = Number($(".visual-group > div:not(.ui-sortable-helper)").index(ui.placeholder)+1);
                },
                over: function(event, ui) {
                    position = Number($(".visual-group > div:not(.ui-sortable-helper)").index(ui.placeholder)+1);
                    //position = ui.placeholder.index();
                    //if (position < iposition) position++;
                },
                stop: function (event, ui) {
                    $('.visual-group').each(function (i) {
                        $(this).attr('data-position', i);

                        data = {
                            action : 'UpdateGroupPosition',
                            id_product: $("input[name='product']").val(),
                            group: $('#selected_group').val(),
                            attribute_group: $(this).data('attribute-group'),
                            position: i
                        };

                        ag.ajax._request(data, null, null);

                    });
                }
            });

            $('.visual-group .content').sortable({
                items: " > .card",
                axis: "y",
                placeholder: "card-ui-state-highlight",
                containment: "parent",
                forcePlaceholderSize: true,
                opacity: 0.6,
                start: function(event, ui) {
                    console.log(ui);
                    /*
                    ui.placeholder.height(ui.helper[0].offsetHeight);

                    position = Number($(".visual-group > div:not(.ui-sortable-helper)").index(ui.placeholder)+1);
                    */
                },
                over: function(event, ui) {
                    //position = Number($(".visual-group > div:not(.ui-sortable-helper)").index(ui.placeholder)+1);
                    //position = ui.placeholder.index();
                    //if (position < iposition) position++;
                },
                stop: function (event, ui) {
                    var $item = ui.item;
                    console.log($item);
                    var $parent = $item.closest('.content');
                    $parent.find('.card').each(function (i) {
                        $card = $(this);
                        $card.attr('data-position', i);

                        data = {
                            action : 'UpdateAttributePosition',
                            id_product: $("input[name='product']").val(),
                            group: $('#selected_group').val(),
                            attribute_value: $card.data('id-attribute'),
                            position: i
                        };

                        ag.ajax._request(data, null, null);
                    });




                    /*
                    $('.visual-group').each(function (i) {
                        $(this).attr('data-position', i);
                    });
                    */
                }
            });

            $( "#sectionA #container, .visual-group" ).disableSelection();

            $('a.toggler').bind('click');
            $('a.toggler').on('click', function (a) {
                a.preventDefault();
                a.stopImmediatePropagation();

                //$('.visual-group.open').removeClass('open');

                $(this).closest('.visual-group').toggleClass('open');
            })
        },
        changeGroup: function (e) {
            let id = parseInt($(e).val());

            if($(e).is(":checked")) {
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
        saveGroup: function () {
            $("div").find(`[data-attr='save']`).find("div").show();

            console.log(ag.attributesToRemove);

            data = {
                action : 'SaveGroup',
                product : $("input[name='product']").val(),
                group : $("label[for='group_"+$('.checkGroup input:checked').val()+"']").text().trim(),
                element : ag.combinationAttributes,
                id_ag_group: $('#selected_group').val(),
                quantity_min: $('#quantity_min').val(),
                quantity_increment: $('#quantity_increment').val(),
                to_remove: ag.attributesToRemove
            };

            if ($('input.group:checked[data-id-ag-group-products]').length)
                data.id_ag_group_product = $('input.group:checked').data('id-ag-group-products');

            ag.ajax._request(data, ag.callbaks.saveSuccess, ag.callbaks.saveError, null, function () {
                $("div").find(`[data-attr='save']`).find("div").hide();
            });
        },
        generateCombinations: function() {
            $("div").find(`[data-attr='generate']`).find("div").css("display","block");

            if (!ag.groups.length) {
                $('.group:checked').each(function() {
                    ag.groups.push(this.value);
                }) ;
            }
            console.log(ag.groups);
            data = {
                action : 'GenerateCombinations',
                ajax: true,
                product : $("input[name='product']").val(),
                group : ag.groups,
                id_ag_group: $('input.group:checked').val(),
            };

            ag.ajax._request(data, ag.callbaks.generateSuccess, ag.callbaks.generateError, null);

        },
        editGroup: function(e) {
            let me = $(e);

            $('#sectionA .card-body#container').html('');
            $('.option:checked').trigger('click');
            $('#groups-table tr').removeClass('active');

            data = {
                action: 'GetGroupSelectedAttributes',
                ajax: true,
                product : $("input[name='product']").val(),
                group : me.data('group')
            };

            me.closest('tr').addClass('active');

            $('#selected_group').val(me.data('group'));

            ag.ajax._request(data, ag.callbaks.reloadGroupSelectedAttributesSuccess, ag.callbaks.reloadGroupSelectedAttributesError, null);

        },
        deleteGroup: function(e) {
            if (confirm("Sei sicuro di voler proseguire con l'eliminazione?")) {
                let me = $(e);
                data = {
                    action : 'DeleteGroup',
                    ajax: true,
                    product : $("input[name='product']").val(),
                    idGroup : me.attr("data-attribute"),
                };

                ag.ajax._request(data, ag.callbaks.deleteSucces, ag.callbaks.deleteError, null);
            }
        },
        addRule: function() {
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

            ag.rules.push(object);
        },
        changeRulesOption: function(e) {
            console.log("ERER");
            let element = "<div style='width:15%;margin:10px' class='card col-sm-offset-1' id=card_"+$(e).attr('id')+">\n" +
                "  <div class=\"card-body\">\n" +
                "   "+$(e).attr('data-name')+"\n" +
                "  </div>\n" +
                "</div>";

            if($(e).is(":checked")) {
                $("#container-3").append(element);
                ag.combinationAttributesRule.push($(e).attr('id'));
            }
            else{
                $('#card_'+$(e).attr('id')+'').remove();
                let index = ag.combinationAttributesTemp.indexOf($(e).attr('id'));
                if (index > -1) {
                    ag.combinationAttributesRule.splice(index, 1);
                }
            }
            //console.log(combinationAttributes);
            $(".option3").val($(e).is(':checked'));

            //console.log(combinationAttributesTemp);
        },
        saveRule: function(e) {
            $("div").find(`[data-attr='saveRule']`).find("div").css("display","block");

            data = {
                action : 'SaveRule',
                ajax: true,
                product : $("input[name='product']").val(),
                rule : ag.rules,
                element : ag.combinationAttributesRule,
            };

            ag.ajax._request(data, ag.callbaks.saveRuleSuccess, ag.callbaks.saveRuleError, null);
        },
        editRule: function(e) {
            //if (confirm('Sei sicuro di voler proseguire con l\'eliminazione?')) {
            let me = $(e);
            data = {
                action: 'EditRule',
                ajax: true,
                product : $("input[name='product']").val(),
                idRule : me.attr("data-attribute-id")
            };

            ag.ajax._request(data, ag.callbaks.editRuleSuccess, ag.callbaks.editRuleError, null);
            //}
        },
        deleteRule: function(e) {
            if (confirm('Sei sicuro di voler proseguire con l\'eliminazione?')) {
                let me = $(e);
                data = {
                    action: 'DeleteRule',
                    ajax: true,
                    product : $("input[name='product']").val(),
                    idRule : me.attr("data-attribute-id")
                };

                ag.ajax._request(data, ag.callbaks.deleteRuleSuccess, ag.callbaks.deleteRuleError, null);
            }
        },
        reloadGroupSelectedAttributes: function(element) {
            let me = $(element);

            data = {
                action: 'GetGroupSelectedAttributes',
                ajax: true,
                product : $("input[name='product']").val(),
                group : me.val()
            };

            ag.ajax._request(data, ag.callbaks.reloadGroupSelectedAttributesSuccess, ag.callbaks.reloadGroupSelectedAttributesError, null);
        },
        reloadRuleGroupAttributes: function(element) {
            let me = $(element);

            data = {
                action: 'GetAttributeGroup',
                ajax: true,
                product : $("input[name='product']").val(),
                group : me.val()
            };

            ag.ajax._request(data, ag.callbaks.reloadGrupAttributesSuccess, ag.callbaks.reloadGrupAttributesError, null);
        },
        manageAccordion: function(element) {
            $(element).collapse();
        },
        init: function () {

            ag.combinationAttributes = [];
            ag.groups = [];

            ag.events.doChange('.option', this.changeOption);
            ag.events.doChange('.checkGroup .group', this.changeGroup);
            ag.events.doClick('#saveGroup', this.saveGroup);
            ag.events.doClick('#generateCombinations', this.generateCombinations);
            ag.events.doClick('.table-group .edit_group', this.editGroup);
            ag.events.doClick('.table-group .delete_group', this.deleteGroup);
            ag.events.doClick('#addRule', this.addRule);
            ag.events.doChange('#accordionModify .option3', this.changeRulesOption);
            ag.events.doClick('#saveRule', this.saveRule);
            ag.events.doClick('.table-rule .edit_attribute_rule', this.editRule);
            ag.events.doClick('.table-rule .delete_attribute_rule', this.deleteRule);
            ag.events.doChange('select[name="select-group"]', this.reloadRuleGroupAttributes);
            ag.events.doClick('#accordion button', this.manageAccordion);



        }
    },
    callbaks: {
        saveSuccess: function (response, element) {
            console.log(response, element);
            if (response.status == true) {
                //if (!$('tr#'))
                if (!$('#groups-table #'))
                $('#groups-table').append(response.html);

            } else {
                alert(response.message);
            }
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
                $tr.remove();
            }
        },
        deleteError: function () {

        },
        saveRuleSuccess: function (response, element) {
            result = JSON.parse(response);

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
        saveRuleError: function () {
            $("div").find(`[data-attr='saveTemp']`).find("div").css("display","none");
        },
        deleteRuleSuccess: function (response, element) {
            let me = $(element);
            me.closest("tr").remove();
            alert("Operazione eseguita con successo");
        },
        deleteRuleError: function (response, element) {
            
        },
        editRuleSuccess: function (response, element) {

            let rule = response.rule;


            $('select[name="select-group"]').val(rule.id_rule).change();
            $('select[name="type_value_rule"]').val(rule.type).change();
            $('input[name="text_rule"]').val(rule.name);
            $('input[name="text_value_rule"]').val(rule.value);
            $('input[name="id_ag_group_edit"]').val(rule.id_rule);

            console.log(response);
            //let me = $(element);
            //console.log(me);
        },
        editRuleError: function (response, element) {
            
        },
        reloadGrupAttributesSuccess: function (response, element) {
            if (response.status) {

                $("#accordionModify").html(response.html);

                $("#container-3").html("");
                $('.option3').prop('checked',false);
            }
        },
        reloadGrupAttributesError: function (response, element) {

        },
        reloadGroupSelectedAttributesSuccess: function (response, element) {
            if (response.attributesGroup.length) {
                attributesGroup = response.attributesGroup;

                $.each(attributesGroup, function (i, item) {
                    if ($('.option#'+item.id_value).is(':checked'))
                        return;
                    //console.log(item.id_value);
                    //$('button[data-target="#collapse'+item.id_attribute+'"]').trigger('click');
                    $('.option#'+item.id_value).trigger('click');
                });
                attributes = response.attributes;
                $.each(attributes, function (i, item) {
                    //console.log(item);
                    $('#accordion button[data-target="#collapse'+item+'"]').click();
                })
            }

        },
        reloadGroupSelectedAttributesError: function (response, element) {
            
        }
    },
    init: function() {
        this.events.init();
    }
}