BX.saleOrderAjax = { // bad solution, actually, a singleton at the page

	BXCallAllowed: false,

	options: {},
	indexCache: {},
	controls: {},

	modes: {},
	properties: {},

	// called once, on component load
	init: function(options)
	{
		var ctx = this;
		this.options = options;

		window.submitFormProxy = BX.proxy(function(){
			ctx.submitFormProxy.apply(ctx, arguments);
		}, this);

		BX(function(){
			ctx.initDeferredControl();
		});
		BX(function(){
			ctx.BXCallAllowed = true; // unlock form refresher
		});

		this.controls.scope = BX('bx-soa-order');

		// user presses "add location" when he cannot find location in popup mode
		BX.bindDelegate(this.controls.scope, 'click', {className: '-bx-popup-set-mode-add-loc'}, function(){

			var input = BX.create('input', {
				attrs: {
					type: 'hidden',
					name: 'PERMANENT_MODE_STEPS',
					value: '1'
				}
			});

			BX.prepend(input, BX('bx-soa-order'));

			ctx.BXCallAllowed = false;
			BX.Sale.OrderAjaxComponent.sendRequest();
		});
	},

	cleanUp: function(){

		for(var k in this.properties)
		{
			if (this.properties.hasOwnProperty(k))
			{
				if(typeof this.properties[k].input != 'undefined')
				{
					BX.unbindAll(this.properties[k].input);
					this.properties[k].input = null;
				}

				if(typeof this.properties[k].control != 'undefined')
					BX.unbindAll(this.properties[k].control);
			}
		}

		this.properties = {};
	},

	addPropertyDesc: function(desc){
		this.properties[desc.id] = desc.attributes;
		this.properties[desc.id].id = desc.id;
	},

	// called each time form refreshes
	initDeferredControl: function()
	{
		var ctx = this,
			k,
			row,
			input,
			locPropId,
			m,
			control,
			code,
			townInputFlag,
			adapter;

		// first, init all controls
		if(typeof window.BX.locationsDeferred != 'undefined'){

			this.BXCallAllowed = false;

			for(k in window.BX.locationsDeferred){

				window.BX.locationsDeferred[k].call(this);
				window.BX.locationsDeferred[k] = null;
				delete(window.BX.locationsDeferred[k]);

				this.properties[k].control = window.BX.locationSelectors[k];
				delete(window.BX.locationSelectors[k]);
			}
		}

		for(k in this.properties){

			// zip input handling
			if(this.properties[k].isZip){
				row = this.controls.scope.querySelector('[data-property-id-row="'+k+'"]');
				if(BX.type.isElementNode(row)){

					input = row.querySelector('input[type="text"]');
					if(BX.type.isElementNode(input)){
						this.properties[k].input = input;

						// set value for the first "location" property met
						locPropId = false;
						for(m in this.properties){
							if(this.properties[m].type == 'LOCATION'){
								locPropId = m;
								break;
							}
						}

						if(locPropId !== false){
							BX.bindDebouncedChange(input, function(value){

								var zipChangedNode = BX('ZIP_PROPERTY_CHANGED');
								zipChangedNode && (zipChangedNode.value = 'Y');

								input = null;
								row = null;

								if(BX.type.isNotEmptyString(value) && /^\s*\d+\s*$/.test(value) && value.length > 3){

									ctx.getLocationsByZip(value, function(locationsData){
										ctx.properties[locPropId].control.setValueByLocationIds(locationsData);
									}, function(){
										try{
											// ctx.properties[locPropId].control.clearSelected();
										}catch(e){}
									});
								}
							});
						}
					}
				}
			}

			// location handling, town property, etc...
			if(this.properties[k].type == 'LOCATION')
			{

				if(typeof this.properties[k].control != 'undefined'){

					control = this.properties[k].control; // reference to sale.location.selector.*
					code = control.getSysCode();

					// we have town property (alternative location)
					if(typeof this.properties[k].altLocationPropId != 'undefined')
					{
						if(code == 'sls') // for sale.location.selector.search
						{
							// replace default boring "nothing found" label for popup with "-bx-popup-set-mode-add-loc" inside
							control.replaceTemplate('nothing-found', this.options.messages.notFoundPrompt);
						}

						if(code == 'slst')  // for sale.location.selector.steps
						{
							(function(k, control){

								// control can have "select other location" option
								control.setOption('pseudoValues', ['other']);

								// insert "other location" option to popup
								control.bindEvent('control-before-display-page', function(adapter){

									control = null;

									var parentValue = adapter.getParentValue();

									// you can choose "other" location only if parentNode is not root and is selectable
									if(parentValue == this.getOption('rootNodeValue') || !this.checkCanSelectItem(parentValue))
										return;

									var controlInApater = adapter.getControl();

									if(typeof controlInApater.vars.cache.nodes['other'] == 'undefined')
									{
										controlInApater.fillCache([{
											CODE:		'other',
											DISPLAY:	ctx.options.messages.otherLocation,
											IS_PARENT:	false,
											VALUE:		'other'
										}], {
											modifyOrigin:			true,
											modifyOriginPosition:	'prepend'
										});
									}
								});

								townInputFlag = BX('LOCATION_ALT_PROP_DISPLAY_MANUAL['+parseInt(k)+']');

								control.bindEvent('after-select-real-value', function(){

									// some location chosen
									if(BX.type.isDomNode(townInputFlag))
										townInputFlag.value = '0';
								});
								control.bindEvent('after-select-pseudo-value', function(){

									// option "other location" chosen
									if(BX.type.isDomNode(townInputFlag))
										townInputFlag.value = '1';
								});

								// when user click at default location or call .setValueByLocation*()
								control.bindEvent('before-set-value', function(){
									if(BX.type.isDomNode(townInputFlag))
										townInputFlag.value = '0';
								});

								// restore "other location" label on the last control
								if(BX.type.isDomNode(townInputFlag) && townInputFlag.value == '1'){

									// a little hack: set "other location" text display
									adapter = control.getAdapterAtPosition(control.getStackSize() - 1);

									if(typeof adapter != 'undefined' && adapter !== null)
										adapter.setValuePair('other', ctx.options.messages.otherLocation);
								}

							})(k, control);
						}
					}
				}
			}
		}

		this.BXCallAllowed = true;

		//set location initialized flag and refresh region & property actual content
		if (BX.Sale.OrderAjaxComponent)
			BX.Sale.OrderAjaxComponent.locationsCompletion();
	},

	checkMode: function(propId, mode){

		//if(typeof this.modes[propId] == 'undefined')
		//	this.modes[propId] = {};

		//if(typeof this.modes[propId] != 'undefined' && this.modes[propId][mode])
		//	return true;

		if(mode == 'altLocationChoosen'){

			if(this.checkAbility(propId, 'canHaveAltLocation')){

				var input = this.getInputByPropId(this.properties[propId].altLocationPropId);
				var altPropId = this.properties[propId].altLocationPropId;

				if(input !== false && input.value.length > 0 && !input.disabled && this.properties[altPropId].valueSource != 'default'){

					//this.modes[propId][mode] = true;
					return true;
				}
			}
		}

		return false;
	},

	checkAbility: function(propId, ability){

		if(typeof this.properties[propId] == 'undefined')
			this.properties[propId] = {};

		if(typeof this.properties[propId].abilities == 'undefined')
			this.properties[propId].abilities = {};

		if(typeof this.properties[propId].abilities != 'undefined' && this.properties[propId].abilities[ability])
			return true;

		if(ability == 'canHaveAltLocation'){

			if(this.properties[propId].type == 'LOCATION'){

				// try to find corresponding alternate location prop
				if(typeof this.properties[propId].altLocationPropId != 'undefined' && typeof this.properties[this.properties[propId].altLocationPropId]){

					var altLocPropId = this.properties[propId].altLocationPropId;

					if(typeof this.properties[propId].control != 'undefined' && this.properties[propId].control.getSysCode() == 'slst'){

						if(this.getInputByPropId(altLocPropId) !== false){
							this.properties[propId].abilities[ability] = true;
							return true;
						}
					}
				}
			}

		}

		return false;
	},

	getInputByPropId: function(propId){
		if(typeof this.properties[propId].input != 'undefined')
			return this.properties[propId].input;

		var row = this.getRowByPropId(propId);
		if(BX.type.isElementNode(row)){
			var input = row.querySelector('input[type="text"]');
			if(BX.type.isElementNode(input)){
				this.properties[propId].input = input;
				return input;
			}
		}

		return false;
	},

	getRowByPropId: function(propId){

		if(typeof this.properties[propId].row != 'undefined')
			return this.properties[propId].row;

		var row = this.controls.scope.querySelector('[data-property-id-row="'+propId+'"]');
		if(BX.type.isElementNode(row)){
			this.properties[propId].row = row;
			return row;
		}

		return false;
	},

	getAltLocPropByRealLocProp: function(propId){
		if(typeof this.properties[propId].altLocationPropId != 'undefined')
			return this.properties[this.properties[propId].altLocationPropId];

		return false;
	},

	toggleProperty: function(propId, way, dontModifyRow){

		var prop = this.properties[propId];

		if(typeof prop.row == 'undefined')
			prop.row = this.getRowByPropId(propId);

		if(typeof prop.input == 'undefined')
			prop.input = this.getInputByPropId(propId);

		if(!way){
			if(!dontModifyRow)
				BX.hide(prop.row);
			prop.input.disabled = true;
		}else{
			if(!dontModifyRow)
				BX.show(prop.row);
			prop.input.disabled = false;
		}
	},

	submitFormProxy: function(item, control)
	{
		var propId = false;
		for(var k in this.properties){
			if(typeof this.properties[k].control != 'undefined' && this.properties[k].control == control){
				propId = k;
				break;
			}
		}

		// turning LOCATION_ALT_PROP_DISPLAY_MANUAL on\off

		if(item != 'other'){

			if(this.BXCallAllowed){

				this.BXCallAllowed = false;
				setTimeout(function(){BX.Sale.OrderAjaxComponent.sendRequest()}, 20);
			}

		}
	},

	getPreviousAdapterSelectedNode: function(control, adapter){

		var index = adapter.getIndex();
		var prevAdapter = control.getAdapterAtPosition(index - 1);

		if(typeof prevAdapter !== 'undefined' && prevAdapter != null){
			var prevValue = prevAdapter.getControl().getValue();

			if(typeof prevValue != 'undefined'){
				var node = control.getNodeByValue(prevValue);

				if(typeof node != 'undefined')
					return node;

				return false;
			}
		}

		return false;
	},
	getLocationsByZip: function(value, successCallback, notFoundCallback)
	{
		if(typeof this.indexCache[value] != 'undefined')
		{
			successCallback.apply(this, [this.indexCache[value]]);
			return;
		}

		var ctx = this;

		BX.ajax({
			url: this.options.source,
			method: 'post',
			dataType: 'json',
			async: true,
			processData: true,
			emulateOnload: true,
			start: true,
			data: {'ACT': 'GET_LOCS_BY_ZIP', 'ZIP': value},
			//cache: true,
			onsuccess: function(result){
				if(result.result)
				{
					ctx.indexCache[value] = result.data;
					successCallback.apply(ctx, [result.data]);
				}
				else
				{
					notFoundCallback.call(ctx);
				}
			},
			onfailure: function(type, e){
				// on error do nothing
			}
		});
	}

};
function changeCalendar(flag) {
	var flag = flag || false;
	var el = $('[id ^= "calendar_popup_"]');
	var links = el.find(".bx-calendar-cell");
	$('.bx-calendar-left-arrow, .bx-calendar-right-arrow').attr({'onclick': 'changeCalendar();',});
	$('.bx-calendar-top-month').attr({'onclick': 'changeMonth();',});
	$('.bx-calendar-top-year').attr({'onclick': 'changeYear();',});

	var startDateIndent = 0,
		utc = 3,
		endDateIntend = 500,
		date = new Date(),
		currentDate = new Date(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(), utc, 0, 0, 0);

	if(flag){
		var hour = new Date().getUTCHours() + utc;

		if(hour > 15){
			startDateIndent = 2;
		}
		else{
			startDateIndent = 1;
		}
	}
	/** todo и пожалуйста, исправьте, чтобы 04.05.2019 после 15.00 нельзя было на сегодня оформить заказ **/
	var hour = new Date().getUTCHours() + utc,
		currentDateStr = date.getUTCDate()+'.'+(date.getUTCMonth()+1)+'.'+date.getUTCFullYear();

	if(hour > 14 && currentDateStr == '04.05.2019'){
		startDateIndent = 1;
	}
	/** endtodo**/

	var endDay = new Date();
	endDay.setDate(currentDate.getDate() + endDateIntend);
	currentDate.setDate(currentDate.getDate() + startDateIndent);

	var currentTime = currentDate.getTime();
	for (var i =0; i <= links.length; i++){
		var atrDate = $(links[i]).attr('data-date');

		atrDate = parseInt( atrDate );

		if (atrDate < currentTime || atrDate > endDay) {
			$('[data-date="' + atrDate +'"]').addClass("bx-calendar-date-hidden disabled");
		}
	}
}

function changeMonth() {
	var el = $('[id ^= "calendar_popup_month_"]');
	var links = el.find(".bx-calendar-month");
	for (var i =0; i <= links.length; i++) {
		var func = $(links[i]).attr('onclick');

		$('[onclick="' + func +'"]').attr({'onclick': func + '; changeCalendar();',});
	}
}
function changeYear() {
	var el = $('[id ^= "calendar_popup_year_"]');
	var links = el.find(".bx-calendar-year-number");
	for (var i =0; i <= links.length; i++) {
		var func = $(links[i]).attr('onclick');
		$('[onclick="' + func +'"]').attr({'onclick': func + '; changeCalendar();',});
	}
}

function changeTime() {
	var selectTime = $('select[name="ORDER_PROP_11"]'),
		selectTimeOption = selectTime.find("option"),
		containerData = $('div[data-property-id-row="13"]'),
		idData = $("#soa-property-13"),
		curDate = new Date();
	var arrSubota = ["04.05.2019","11.05.2019","16.11.2019"];

	if(idData.val() !== ""){
		var strNewDate = idData.val().split('.');//.replace(/(\d+)\.(\d+)\.(\d+)/, '$3,$2,$1');
		var selectDate = new Date(strNewDate[2],strNewDate[1]-1,strNewDate[0]);
		// console.log(idData.val());
		// console.log(strNewDate);
		// console.log(selectDate);
		$(".gmi_tooltip").detach();
		if(selectDate.getDay()==0){
			if(!containerData.children().hasClass('gmi_tooltip')){
				containerData.prepend('<div id="tooltip-soa-property-13" class="bx-soa-tooltip bx-soa-tooltip-static bx-soa-tooltip-danger tooltip top gmi_tooltip" data-state="opened" style="opacity: 1; display: block;"><div class="tooltip-arrow"></div><div class="tooltip-inner">В воскресенье нет доставки</div></div>');
			}
			idData.val('');
			selectTime.val('');
			$(selectTimeOption[0]).attr('disabled',true);
			$(selectTimeOption[1]).attr('disabled',true);
			$(selectTimeOption[2]).attr('disabled',true);
			//$(selectTimeOption[3]).attr('disabled',true);
		}else if(
			idData.val() == "07.01.2019" ||
			idData.val() == '01.05.2019' ||
			idData.val() == '06.05.2019' ||
			idData.val() == '07.05.2019' ||
			idData.val() == '08.05.2019' ||
			idData.val() == '09.05.2019' ||
			idData.val() == '03.07.2019' ||
			idData.val() == '07.11.2019' ||
			idData.val() == '08.11.2019' ||
			idData.val() == '25.12.2019' ||
			idData.val() == "08.03.2019"
		){
			if(!containerData.children().hasClass('gmi_tooltip')){
				containerData.prepend('<div id="tooltip-soa-property-13" class="bx-soa-tooltip bx-soa-tooltip-static bx-soa-tooltip-danger tooltip top gmi_tooltip" data-state="opened" style="opacity: 1; display: block;"><div class="tooltip-arrow"></div><div class="tooltip-inner">В праздничные дни нет доставки</div></div>');
			}
			idData.val('');
			selectTime.val('');
			$(selectTimeOption[0]).attr('disabled',true);
			$(selectTimeOption[1]).attr('disabled',true);
			$(selectTimeOption[2]).attr('disabled',true);
			$(selectTimeOption[3]).attr('disabled',true);
		}else if(selectDate.getDay()==6 && jQuery.inArray(idData.val(), arrSubota)== -1){
			if(!containerData.children().hasClass('gmi_tooltip')){
				containerData.prepend('<div id="tooltip-soa-property-13" class="bx-soa-tooltip bx-soa-tooltip-static bx-soa-tooltip-danger tooltip top gmi_tooltip" data-state="opened" style="opacity: 1; display: block;"><div class="tooltip-arrow"></div><div class="tooltip-inner">В субботу нет доставки</div></div>');
			}
			idData.val('');
			selectTime.val('');
			$(selectTimeOption[0]).attr('disabled',true);
			$(selectTimeOption[1]).attr('disabled',true);
			$(selectTimeOption[2]).attr('disabled',true);
			//$(selectTimeOption[3]).attr('disabled',true);
			// $(selectTimeOption[0]).attr('disabled',true);
			// $(selectTimeOption[1]).attr('disabled',true);
			// $(selectTimeOption[2]).attr('disabled',true);
			// $(selectTimeOption[3]).attr('disabled',false);
			// selectTime.attr('disabled',false);
			// selectTime.prop('selectedIndex',3);
		}else if(curDate.getDay() == selectDate.getDay() && curDate.getMonth() == selectDate.getMonth() && curDate.getFullYear() == selectDate.getFullYear()){
			selectTime.val('');
			//alert(curDate.getHours());
			/*if(curDate.getHours()>=16){
				if(!containerData.children().hasClass('gmi_tooltip')){
					containerData.prepend('<div id="tooltip-soa-property-13" class="bx-soa-tooltip bx-soa-tooltip-static bx-soa-tooltip-danger tooltip top gmi_tooltip" data-state="opened" style="opacity: 1; display: block;"><div class="tooltip-arrow"></div><div class="tooltip-inner">Доступна доставка только на завтра</div></div>');
				}
				$(selectTimeOption[0]).attr('disabled',true);
				$(selectTimeOption[1]).attr('disabled',true);
				//$(selectTimeOption[3]).attr('disabled',true);
			}else if(curDate.getHours()>=10){
				$(selectTimeOption[0]).attr('disabled',true);
				$(selectTimeOption[1]).attr('disabled',false);
				//$(selectTimeOption[3]).attr('disabled',true);
			}else{
				$(selectTimeOption[0]).attr('disabled',false);
				$(selectTimeOption[1]).attr('disabled',false);
				//$(selectTimeOption[3]).attr('disabled',true);
			}*/



            $(selectTimeOption[0]).attr('disabled',true);
            $(selectTimeOption[1]).attr('disabled',true);
            $(selectTimeOption[2]).attr('disabled',true);
            $(selectTimeOption[3]).attr('disabled',true);

            //12-16 доступен для оформления до 10.00
           /* if(curDate.getHours()< 10){
                $(selectTimeOption[0]).attr('disabled',false);
            }*/


			//12-16 доступен для оформления до 9.45 текущей даты
			if(curDate.getHours() < 9 || (curDate.getHours() == 9 && curDate.getMinutes() <= 45)){
				$(selectTimeOption[0]).attr('disabled',false);
			}

            //12-18 доступен для оформления до 9.30 текущей даты
            if(curDate.getHours() < 9 || (curDate.getHours() == 9 && curDate.getMinutes() <= 30)){
                $(selectTimeOption[1]).attr('disabled',false);
            }

            //18-21 доступен для оформления с 9.30 до 16.00
            //19-22 доступен для оформления до 16.00
            if(curDate.getHours() < 16){
                $(selectTimeOption[2]).attr('disabled',false);
                $(selectTimeOption[3]).attr('disabled',false);
            }

            if(curDate.getHours()>=16){
                if(!containerData.children().hasClass('gmi_tooltip')){
                    containerData.prepend('<div id="tooltip-soa-property-13" class="bx-soa-tooltip bx-soa-tooltip-static bx-soa-tooltip-danger tooltip top gmi_tooltip" data-state="opened" style="opacity: 1; display: block;"><div class="tooltip-arrow"></div><div class="tooltip-inner">Доступна доставка только на завтра</div></div>');
                }
                $(selectTimeOption[0]).attr('disabled',true);
                $(selectTimeOption[1]).attr('disabled',true);
                $(selectTimeOption[2]).attr('disabled',true);
                $(selectTimeOption[3]).attr('disabled',true);
            }


		}
		else{
            $(selectTimeOption[0]).attr('disabled',false);
            $(selectTimeOption[1]).attr('disabled',false);
            $(selectTimeOption[2]).attr('disabled',false);
            $(selectTimeOption[3]).attr('disabled',false);
            selectTime.val('');
            selectTime.prop('selectedIndex',2);



			/*$(selectTimeOption[0]).attr('disabled',false);
			$(selectTimeOption[1]).attr('disabled',false);
			//$(selectTimeOption[3]).attr('disabled',true);
			selectTime.val('');
			selectTime.prop('selectedIndex',2);*/
		}
	}else if(idData.val() === ""){
		if(!containerData.hasClass("has-error")){
			containerData.prepend('<div id="tooltip-soa-property-13" class="bx-soa-tooltip bx-soa-tooltip-static bx-soa-tooltip-danger tooltip top" data-state="opened" style="opacity: 1; display: block;"><div class="tooltip-arrow"></div><div class="tooltip-inner">Поле "Дата доставки" обязательно для заполнения</div></div>');
		}
		containerData.addClass("has-error");
	}
}

function gmiShowErr() {
	var containerData = $('div[data-property-id-row="21"]'),
		idData = $("#soa-property-21"),
		curDate = new Date();

	if(idData.val() !== ""){
		var strNewDate = idData.val().split('.');//.replace(/(\d+)\.(\d+)\.(\d+)/, '$3,$2,$1');
		var selectDate = new Date(strNewDate[2],strNewDate[1]-1,strNewDate[0]);
		$(".gmi_tooltip").detach();
		var arrSubota = ["04.05.2019","11.05.2019","16.11.2019"];

		if(selectDate.getDay()==0){
			if(!containerData.children().hasClass('gmi_tooltip')){
				containerData.prepend('<div id="tooltip-soa-property-13" class="bx-soa-tooltip bx-soa-tooltip-static bx-soa-tooltip-danger tooltip top gmi_tooltip" data-state="opened" style="opacity: 1; display: block;"><div class="tooltip-arrow"></div><div class="tooltip-inner">В воскресенье нет доставки</div></div>');
			}
			idData.val('');
		}else if(
			idData.val() == "07.01.2019" ||
			idData.val() == '01.05.2019' ||
			idData.val() == '06.05.2019' ||
			idData.val() == '07.05.2019' ||
			idData.val() == '08.05.2019' ||
			idData.val() == '09.05.2019' ||
			idData.val() == '03.07.2019' ||
			idData.val() == '07.11.2019' ||
			idData.val() == '08.11.2019' ||
			idData.val() == '25.12.2019' ||
			idData.val() == "08.03.2019"
			){
			if(!containerData.children().hasClass('gmi_tooltip')){
				containerData.prepend('<div id="tooltip-soa-property-13" class="bx-soa-tooltip bx-soa-tooltip-static bx-soa-tooltip-danger tooltip top gmi_tooltip" data-state="opened" style="opacity: 1; display: block;"><div class="tooltip-arrow"></div><div class="tooltip-inner">В праздничные дни нет доставки</div></div>');
			}
			idData.val('');
		}else if(selectDate.getDay()==6 && jQuery.inArray(idData.val(), arrSubota)== -1){
			if(!containerData.children().hasClass('gmi_tooltip')){
				containerData.prepend('<div id="tooltip-soa-property-13" class="bx-soa-tooltip bx-soa-tooltip-static bx-soa-tooltip-danger tooltip top gmi_tooltip" data-state="opened" style="opacity: 1; display: block;"><div class="tooltip-arrow"></div><div class="tooltip-inner">В субботу нет доставки</div></div>');
			}
			idData.val('');
		}else if(curDate.getDay() == selectDate.getDay() && curDate.getMonth() == selectDate.getMonth() && curDate.getFullYear() == selectDate.getFullYear()){
			//alert(curDate.getHours());
			if(curDate.getHours()>=16){
				if(!containerData.children().hasClass('gmi_tooltip')){
					containerData.prepend('<div id="tooltip-soa-property-13" class="bx-soa-tooltip bx-soa-tooltip-static bx-soa-tooltip-danger tooltip top gmi_tooltip" data-state="opened" style="opacity: 1; display: block;"><div class="tooltip-arrow"></div><div class="tooltip-inner">Доступна доставка только на завтра</div></div>');
				}
			}
		}
	}else if(idData.val() === ""){
		if(!containerData.hasClass("has-error")){
			containerData.prepend('<div id="tooltip-soa-property-13" class="bx-soa-tooltip bx-soa-tooltip-static bx-soa-tooltip-danger tooltip top" data-state="opened" style="opacity: 1; display: block;"><div class="tooltip-arrow"></div><div class="tooltip-inner">Поле "Дата доставки" обязательно для заполнения</div></div>');
		}
		containerData.addClass("has-error");
	}
}


function searchSteet(){
	var city = $(".bx-sls .bx-ui-sls-input-block .dropdown-field").val();
	var street = $("#soa-property-16").val();
	console.log(city.length);
	if(typeof(city) == 'undefined' || city.length==0){
		city = "0000000143";
	}
	$.ajax({
		url: '/include/get_street.php',
		data: {"NAME_STREET":street, "CODE_CITY":city},
		type: "POST",
		success:function(data){
			if(!$(".gmi-street-list").length){
				$('div[data-property-id-row="16"]').append('<div class="gmi-street-list">'+data+'</div>');
			}else{
				$('.gmi-street-list').html(data);
			}
		}
	});
}

window.locationUpdated = function(id)
{
 console.log(arguments);
 console.log(this.getNodeByLocationId(id));
}
$(document).ready(function(){
	$(document).on("click","#soa-property-13",function(){
		BX.calendar({node: this, value: new Date(), field: this, bTime: false});
		changeCalendar();
	});
	$(document).on("click","#soa-property-21",function(){
		BX.calendar({node: this, value: new Date(), field: this, bTime: false});
		changeCalendar(true);
	});
	$(document).on("change","#soa-property-13",function(){
		changeTime();
	});
	$(document).on("change","#soa-property-21",function(){
		gmiShowErr();
	});
	$(document).on("change",".bx-sls .bx-ui-sls-input-block .dropdown-field",function(){
		$("#soa-property-16").val();
	});
	$(document).on("keyup","#soa-property-16",function() {
		if($(this).val().length >= 3){
			searchSteet();
			$(".gmi-street-list").css("display","block");
		}
	});
	$(document).on("click",'.gmi-street-list ul li',function(){
		$("#soa-property-16").val($(this).text());
		$(".gmi-street-list").css("display","none");
	});
	$(document).on("focusout","#soa-property-16", function(){
		setTimeout(function(){
			$(".gmi-street-list").css("display","none");
		},200);
	});
});
