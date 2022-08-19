define(['uiComponent', 'jquery'], function (Component, $) {
    return Component.extend({
        defaults: {
            classLabel: '',
            classInput: '',
            searchText: '',
            searchResult: [],
            minChars: 3
        },
        initObservable: function () {
            this._super();
            this.observe(['searchText', 'searchResult']);
            return this;
        },
        initialize: function (config){
            this._super();
            this.classLabel = config.classLabel;
            this.classInput = config.classInput;
            this.searchText.subscribe(this.handleAutocomplete.bind(this));
        },
        handleAutocomplete: function(searchValue) {
            if (searchValue.length >= this.minChars) {
                $.ajax({
                    url: '/hamenok/product/FilterSku',
                    method: 'get',
                    dataType: 'json',
                    data: {sku: searchValue},
                    success: function(data){
                        this.searchResult(data);
                    }.bind(this)
                });
            } else {
                this.searchResult([]);
            }
        }
    });
});