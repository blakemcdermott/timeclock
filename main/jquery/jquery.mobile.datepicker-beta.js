(function( $, undefined ) {
    $.widget("mobile.date",{
      options:{
		inline: false,            // True to set the calendar always visible
		theme: "a"                // Default mobile theme
      },
      _getCreateOptions: function(){
		$.extend( this.options, $.datepicker._defaults );
		return this._super();
      },
      _create:function(){
        var calendar, interval,
          that = this;
        
        $.each([ 'onSelect', 'onChangeMonthYear', 'beforeShow' ], function(key, val){
        		that.options[ '_'+val ] = that.options[ val ];
        		that.options[ val ] = function(){
    				var args = arguments;
    				if (val == 'onSelect') {
    					that.element.trigger( "change" );
    				}
    				setTimeout(function(){
    					that.addMobileStyle();
    					if (that.options[ '_'+val ]) {
    						that.options[ '_'+val ].apply( null, args );
    					}
    				}, 0);
    			}
        });
        
        if( this.options.inline ){
	        this.options.altField = this.element;
          calendar = $("<div>").datepicker(this.options);
          this.element.parent().after(calendar);
        } else {
          this.element.datepicker( this.options );
          calendar= this.element.datepicker( "widget" );
        }

        this.calendar = calendar;

        this.baseWidget = ( !this.options.inline )? this.element: this.calendar;

        this._on({
          "change": function() {
            if( this.options.inline ){
              this.calendar.datepicker( "setDate", this.element.val() );
            }
            this._delay( "addMobileStyle", 10 );
          },
          "input": function() {
            interval = window.setInterval( function(){
              if( !that.calendar.hasClass( "mobile-enhanced" ) ){
                that.addMobileStyle();
              } else {
                clearInterval( interval );
              }
            });
          }
        });
        this.addMobileStyle();
      },
      setOption:function( key, value ){
        this.calendar.datepicker("option",key,value);
      },
      getDate: function(){
        console.log( this.baseWidget );
        return this.baseWidget.datepicker("getDate");
      },
      _destroy: function(){
        return this.baseWidget.datepicker("destroy");
      },
      isDisabled: function(){
        return this.baseWidget.datepicker("isDisabled");
      },
      refresh: function(){
        return this.baseWidget.datepicker("refresh");
      },
      setDate: function( date ){
        return this.baseWidget.datepicker("setDate", date );
      },
      widget:function(){
       return this.element;
      },
      theme: 'a',
      addMobileStyle: function(){
          this.calendar.addClass("ui-shadow")
          .find( ".ui-datepicker-calendar" ).addClass( "mobile-enhanced" ).end()
          .find(".ui-datepicker-calendar a,.ui-datepicker-prev,.ui-datepicker-next").addClass("ui-btn").end()
          .find(".ui-datepicker-prev").addClass("ui-btn-icon-notext ui-btn-inline ui-corner-all ui-icon-arrow-l ui-shadow").end()
          .find(".ui-datepicker-next").addClass("ui-btn-icon-notext ui-btn-inline ui-corner-all ui-icon-arrow-r ui-shadow").end()
          .find(".ui-datepicker-header").addClass("ui-body-"+this.options.theme+" ui-corner-top").removeClass("ui-corner-all").end()
          .find(".ui-datepicker-calendar th" ).addClass("ui-bar-"+this.options.theme).end()
          .find(".ui-datepicker-calendar td" ).addClass("ui-body-"+this.options.theme).end()
          .find(".ui-datepicker-calendar a.ui-state-active").addClass("ui-btn-active").end()
          .find(".ui-datepicker-calendar a.ui-state-highlight").addClass("ui-btn-up-"+this.options.theme).end().find(".ui-state-disabled").css("opacity","1");
      }
    });

 })( jQuery );