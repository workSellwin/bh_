window.Pager = function (params) {

	/**
	 * селектор контейнера в котром нахоидится контент страницы
	 * @type {string}
	 */
	params.contentContainerId = '#ts-pager-content';
	/**
	 * селектор контейнера пагинатора
	 * @type {string}
	 */
	params.parerContainerId = '#ts-pager-container';
	/**
	 * Селектор текста (для замены во время загрузки)
	 * @type {string}
	 */
	params.pagerTextCotainerId = '#ts-pager-text';


	this.inProgress = false;
	this.params = params;

	this.init();

};


window.Pager.prototype = {
	init: function () {
		var pager = this;

		// клик по кнопке показать еще
		$(document).on('click', this.params.parerContainerId, function (e) {
			pager.nextPage()
		});
	},

	nextPage: function () {

		/**
		 * url следующей страницы
		 * @type {string}
		 */
		this.params.nextPageUrl = this.params.sUrlPathParams + 'PAGEN_1=' + (this.params.NavNum + 1);
		var pager = this;


		/**
		 * Перезагрузим стандартный блок пагинации чтобы страница в нем соответствовала последней загруженной
		 */
		$('#pagination').load(pager.params.nextPageUrl + ' #pagination');

		if(!pager.inProgress) {
			$.ajax({
				url: pager.params.nextPageUrl,
				method: 'GET',
				// data: {"PAGEN_1": nextPageNum, 'AJAX_CALL': 'Y'},
				beforeSend: function () {
					/**
					 * Отметим что блок загружается
					 * @type {boolean}
					 */
					pager.inProgress = true;
					$(pager.params.pagerTextCotainerId).text(BX.message('T_PAGI_LOADING'));
				}
			})
				.done(function (data) {

					if (data.length > 0) {

						/**
						 * Добавляем контент следующей страницы
						 */
						$(pager.params.contentContainerId).append($(data).find(pager.params.contentContainerId).children());

						pager.inProgress = false;
						pager.params.NavNum++;
						pager.params.nextPageNum = pager.params.NavNum + 1;

						/**
						 * Возвращаем кнопку в исходное положение
						 */
						$(pager.params.pagerTextCotainerId).text(BX.message('T_PAGI_MORE'));

						/**
						 * На последней странице удалим кнопку
						 */
						if (pager.params.nextPageNum > pager.params.NavPageCount) {

							$(pager.params.parerContainerId).remove();
						}
					}

				});
		}

	}

}