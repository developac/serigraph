<div class="container">

<ul class="nav nav-tabs">

    <li class="active" style="margin-right: 10px">
        <a data-toggle="tab" href="#sectionA" style="padding: 20px;border: 1px solid;">
            Crea Gruppi
        </a>
    </li>
    <li style="margin-right: 10px">
        <a data-toggle="tab" href="#sectionC" style="padding: 20px;border: 1px solid;">
            Imposta regole prezzo
        </a>
    </li>
    <li>
        <a data-toggle="tab" href="#sectionB" style="padding: 20px;border: 1px solid;">
            Imposta valori temporali
        </a>
    </li>

</ul>
<br>
<div class="tab-content">

    <div id="sectionA" class="tab-pane fade in active show">

        <div class="">
            <div class="row">
                <div class="col-md-12">
                    <h2>{l s='Usa un set di combinazioni preconfigurato' mod='aggregatecombination'}</h2>
                    <div class="card" style="padding:20px">
                        <div class="checkGroup">
                            {foreach $groups as $key => $value}
                                <div class="form-check">
                                    <input class="form-check-input group" type="checkbox" id="group_{$value['id_ag_group']}" value="{$value['id_ag_group']}">
                                    <label class="form-check-label" for="group_{$value['id_ag_group']}">
                                        {$value['name']}
                                    </label>
                                </div>
                            {/foreach}
                        </div>
                        <div style="display:flex">
                            <button id="generateCombinations"style="margin-top: 15px;margin-bottom: 15px;margin-right: 5px">Genera</button>
                            <button id="export" style="margin-top: 15px;margin-bottom: 15px;margin-right: 5px">Esporta</button>
                            <div data-attr="generate">
                                <div class="js-spinner spinner hide btn-primary-reverse onclick mr-1 btn"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">

                    <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    {l s='Importa configurazione da file' mod='aggregatecombination'}
                    </a>
                    <div class="collapse" id="collapseExample">
                        <div class="card" style="padding:20px">
                            <div style="display:flex">
                                <button id="import" style="margin-top: 15px;margin-bottom: 15px;margin-right: 5px">Importa</button> <input type="file" accept=".csv" id="file_to_import" style="margin-top: 15px"/>
                                <div data-attr="import">
                                    <div class="js-spinner spinner hide btn-primary-reverse onclick mr-1 btn"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body form-inline" id="container">

                        </div>
                    </div>
                    <div style="display:flex">
                        <button id="saveGroup" style="margin-top: 15px;margin-bottom: 15px;margin-right: 5px">Salva</button>
                        <div data-attr="save">
                            <div class="js-spinner spinner hide btn-primary-reverse onclick mr-1 btn"></div>
                        </div>
                    </div>
                    <br>
                    <h3>{l s='Gruppi associati a questo prodotto' mod='aggregatecombination'}</h3>
                    <table class="table table-group">
                        <thead>
                        <tr>
                            <th scope="col">Gruppo</th>
                            <th scope="col">Azioni</th>
                        </tr>
                        </thead>
                        <tbody>
                         {foreach $groups as $key => $value}
                            {if $value["checked"] eq "checked=checked"}
                            <tr>
                                <td>{$value["name"]}</td>
                                <td><button class="delete_group" data-attribute="{$value["id_ag_group"]}">Elimina</button></td>
                            </tr>
                            {/if}
                        {/foreach}
                        </tbody>
                    </table>
                </div>
                <div class="col-md-3">
                    <div id="accordion">
                        {foreach $columnHeader as $key => $value}
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{$key}" aria-expanded="true" aria-controls="collapseOne">
                                            {$value}
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapse{$key}" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="card-body">
                                        {foreach $attributeOption[$key] as $o => $option}
                                            <div class="form-check">
                                                <input type="hidden" name="product" value="{$id_product}"/>
                                                <input class="form-check-input option" type="checkbox" value="" data-attribute-group="{$key}" data-name="{$option}" id="{$o}">
                                                <label class="form-check-label" for="{$o}">
                                                    {$option}
                                                </label>
                                            </div>
                                        {/foreach}
                                    </div>
                                </div>
                            </div>
                        {/foreach}
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="sectionB" class="tab-pane fade">

        <div class="row">
            <div class="col-md-9">
                <h2>{l s='Imposta aumenti di prezzo per valore temporali' mod='aggregatecombination'}</h2>
                <br>
                <div CLASS="card" style="padding:15px">
                    <div class="form-check">
                        <label class="form-check-label" for="optionTemp">
                            Scegli un valore di tipo temporale
                        </label>
                        {html_options name=optionTemp options=$attributeTemp}
                    </div>
                    </br>
                    <div class="form-check">
                        <label class="form-check-label" for="name_group">
                            Immetti Valore
                        </label>
                        <input type="text" name="text_value" value=""/>
                        <select name="type_value">
                            <option value="%">Percentuale</option>
                            <option value="€">Valore</option>
                        </select>
                    </div>
                    <br>
                    <div class="form-check">
                        <button id="add">Aggiungi</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body form-inline" id="container-2">

                    </div>
                </div>
                <div style="display:flex">
                    <button id="saveTemp">Salva</button>
                    <div data-attr="saveTemp">
                        <div class="js-spinner spinner hide btn-primary-reverse onclick mr-1 btn"></div>
                    </div>
                </div>
                <br>
                <table class="table table-temp">
                    <thead>
                    <tr>
                        <th scope="col">Valore Temporale</th>
                        <th scope="col">Combinato con</th>
                        <th scope="col">Valore</th>
                        <th scope="col">Azioni</th>
                    </tr>
                    </thead>
                    <tbody>
                        {foreach $tableValueTemp as $price => $value}
                            {foreach $value as $array}
                            <tr>
                                {foreach $array as $row}
                                    <td>{$row["attribute_temp"]}</td>
                                    <td>{$row["attribute"]}</td>
                                    <td>{$row["valore"]}</td>
                                    <td><button class="delete_attribute_temp" data-attribute-temp="{$row["values_temp"]}" data-attribute="{$row["values_attribute"]}">Elimina</button></td>
                                {/foreach}
                            </tr>
                            {/foreach}
                        {/foreach}
                    </tbody>
                </table>
            </div>
            <div class="col-md-3">
                <div id="accordion">
                    {foreach $columnHeader as $key => $value}
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapse2{$key}" aria-expanded="true" aria-controls="collapseOne">
                                        {$value}
                                    </button>
                                </h5>
                            </div>

                            <div id="collapse2{$key}" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    {foreach $attributeOption[$key] as $o => $option}
                                        <div class="form-check">
                                            <input type="hidden" name="product" value="{$id_product}"/>
                                            <input class="form-check-input option-2" type="checkbox" value="" data-attribute-group="{$key}" data-name="{$option}" id="{$o}">
                                            <label class="form-check-label" for="{$o}">
                                                {$option}
                                            </label>
                                        </div>
                                    {/foreach}
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>

    </div>

    <div id="sectionC" class="tab-pane fade in active">

        <div class="row">

            <div class="col-md-9">
                <h2>{l s='Imposta regole di prezzi per ogni gruppo associato al prodotto' mod='aggregatecombination'}</h2>
                <br>
                <div CLASS="card" style="padding:15px">
                    <div class="form-check">
                        <label class="form-check-label" for="name_rule">
                            Nome Rogola
                        </label>
                        <input type="text" name="text_rule" value=""/>
                    </div>
                    <br>
                    <div class="form-check">
                        <label class="form-check-label" for="optionTemp">
                            Seleziona gruppo
                        </label>
                        <select name="select-group">
                            {foreach $groups as $key => $value}
                                {if $value["checked"] eq "checked=checked"}
                                    <option value="{$value["id_ag_group"]}">{$value["name"]}</option>
                                {/if}
                            {/foreach}
                            <input type="hidden" name="id_ag_group" value="0">
                        </select>
                    </div>
                    </br>
                    <div class="form-check">
                        <label class="form-check-label" for="name_group">
                            Immetti Valore
                        </label>
                        <input type="text" name="text_value_rule" value=""/>
                        <select name="type_value_rule">
                            <option value="%">%</option>
                            <option value="€">€</option>
                        </select>
                    </div>
                    <br>
                    <div class="form-check">
                        <button id="addRule">Aggiungi</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body form-inline" id="container-3">

                    </div>
                </div>
                <div style="display:flex">
                    <button id="saveRule">Salva</button>
                    <div data-attr="saveRule">
                        <div class="js-spinner spinner hide btn-primary-reverse onclick mr-1 btn"></div>
                    </div>
                </div>
                <br>

                <table class="table table-rule">
                    <thead>
                    <tr>
                        <th scope="col">Regola Prezzo</th>
                        <th scope="col">Gruppo</th>
                        <th scope="col">Attributi</th>
                        <th scope="col">Valore</th>
                        <th scope="col">Azioni</th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach $tableValueRule as $idRule => $value}
                        {foreach $value as $row}
                            <tr>
                                <td value="{$idRule}">{$row["name"]}</td>
                                <td>{$row["group"]}</td>
                                <td>{$row["attribute"]}</td>
                                <td>{$row["valore"]}</td>
                                <td><button class="delete_attribute_rule" data-attribute-id="{$idRule}">Elimina</button></td>
                            </tr>
                        {/foreach}
                    {/foreach}
                    </tbody>
                </table>

            </div>

            <div class="col-md-3">
                <div id="accordionModify">
                    {foreach $columnHeaderGroup as $key => $value}
                        <div class="card">
                            <div class="card-header" id="headingTree">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{$key}" aria-expanded="true" aria-controls="collapseOne">
                                        {$value}
                                    </button>
                                </h5>
                            </div>

                            <div id="collapse{$key}" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    {foreach $attributeOptionGroup[$key] as $o => $option}
                                        <div class="form-check">
                                            <input type="hidden" name="product" value="{$id_product}"/>
                                            <input class="form-check-input option3" type="checkbox" value="" data-attribute-group="{$key}" data-name="{$option}" id="{$o}">
                                            <label class="form-check-label" for="{$o}">
                                                {$option}
                                            </label>
                                        </div>
                                    {/foreach}
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>

        </div>

    </div>

</div>


</div>
