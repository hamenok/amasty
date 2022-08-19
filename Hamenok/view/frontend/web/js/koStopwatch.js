define(['uiComponent', 'jquery'], function (Component, $) {
    return Component.extend({
        defaults: {
            hours: 0,
            minutes: 0,
            seconds: 0,
            t:0
        },
        initObservable: function () {
            this._super();
            this.observe(['hours','minutes','seconds']);
            return this;
        },
        initialize: function (){
            this._super();
        },
        check: function () {
            this.tick();
            this.start;
        },
        tick: function () {
            this.seconds(parseInt(this.seconds()+1));
            if (this.seconds() >= 60) {
                this.seconds(0);
                this.minutes(this.minutes()+1);
                if (this.minutes() >= 60) {
                    this.minutes(0);
                    this.hours(this.hours()+1);
                }
            }
        },
        start: function() {
            this.t=setInterval(function () {this.check();}.bind(this), 1000);
        },
        stop: function (){
            this.pause();
            this.hours(0);
            this.minutes(0);
            this.seconds(0);
        },
        pause: function () {
            clearTimeout(this.t);
        }
    });
});