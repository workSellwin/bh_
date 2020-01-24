function JCTitleSearchGmi(arParams){
	$("#"+arParams.INPUT_ID).on("keyup",function() {
		if($(this).val().length >= 2){
			BX.ajax.post(
				arParams.AJAX_PAGE,
				{
					'ajax_call':'y',
					'INPUT_ID': arParams.INPUT_ID,
					'q': $(this).val(),
					'l':arParams.MIN_QUERY_LEN
				},
				function(result)
				{
					$("#" + arParams.RESULT_MAIN).html(result).css("display","block");
				}
			);
		}
		else{
			$("#" + arParams.RESULT_MAIN).css("display","none");
		}
	});
	$("#"+arParams.INPUT_ID).on("focus", function(){
		if($(this).val().length >= 2 && $("#" + arParams.RESULT_MAIN).html().length > 0){
			$("#" + arParams.RESULT_MAIN).css("display","block");
		}
	});

	$("#"+arParams.INPUT_ID).on( "focusout", function(){
		setTimeout(function(){
			$("#" + arParams.RESULT_MAIN).css("display","none");
		},200);
	});
}