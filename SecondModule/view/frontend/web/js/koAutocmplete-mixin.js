define(['jquery'], function ($) {
    var mixin = {
        defaults: {
            minChars: 5
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
})





