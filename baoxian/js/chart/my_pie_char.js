 var  $j = jQuery.noConflict();

 $j(document).ready(function(){
	
	function getDimensions(element){
	 	var ret = {};

	 	// The multiple acquisitions of the CSS styles are required to cover any border and padding the elements may have.
	 	// The Ternary (parseInt(...) || 0) statements fix a bug in IE6 where it returns NaN,
	 	//  which doesn't play nicely when adding to numbers...
	 	ret.width = $j(element).width()
	 	  + (parseInt($j(element).css('borderLeftWidth')) || 0)
	 	  + (parseInt($j(element).css('borderRightWidth')) || 0)
	 	  + (parseInt($j(element).css('padding-left')) || 0)
	 	  + (parseInt($j(element).css('padding-right')) || 0);
	 	ret.height = $j(element).height()
	 	  + (parseInt($j(element).css('borderTopWidth')) || 0)
	 	  + (parseInt($j(element).css('borderBottomWidth')) || 0)
	 	  + (parseInt($j(element).css('padding-bottom')) || 0)
	 	  + (parseInt($j(element).css('padding-bottom')) || 0);
	 	var offsets = $j(element).offset();
	 	ret.left = offsets.left;
	 	ret.top = offsets.top;

	 	return ret;
	 }

/*
	  
	var values = [],
        labels = [];
    $j("table.pie-data tbody tr").each(function () {
        values.push(parseInt($j("td", this).text(), 10));
        labels.push($j("th", this).text());
    });
    //alert('ddddddd');
    //$("table.pie-data").hide();
    
    //Raphael("holder", 60, 60).pieChart(30, 30, 30, values, labels, "#fff");
	
	//var res = $j("div.pie-chart-holder").data("values");
	//alert(res);
  */ 
	$j("div.pie-chart-holder").each(function(){
    	e = $j(this);
    	e.removeClass("pie-chart-holder");
    	s = getDimensions(e).height;
		
		/*
		 Raphael(obj[0],s,s).piechart(s/2, s/2, s*.45, values, labels, "#fff");
				
		//e.data("values","dsfsdf");
		//alert(e.attr("data-values"));
		//alert(e.data("values"));
		//.evalJSON();
		//[55, 20, 13, 32, 5, 1, 2, 10]
		//stringify
		
		pie = r.piechart(s/2, s/2, s*.45,e.data("values"), 
	{ legend: ["%%.%% - Enterprise Users", "IE Users"], legendpos: "west", href: ["http://raphaeljs.com", "http://g.raphaeljs.com"], colors:["#FFA035","#FFDF5E"]
	});
		
		//var cc = JSON.stringify(e.data("strokewidth"),e.data("href"),e.data("colors"));
		*/
		
		var cc =  {
			       strokewidth:e.data("strokewidth"),
				   href:e.data("href"),
				   colors:e.data("colors"),
			       };
		var jsonText = JSON.stringify(cc); 
		//alert(jsonText);
		var jsonobj=eval('('+jsonText+')');
		
		var r = Raphael(e[0],s,s),
            pie = r.piechart(s/2, s/2, s*.45,e.data("values"), jsonobj );

                r.text(320, 100, "Interactive Pie Chart").attr({ font: "20px sans-serif" });
                pie.hover(function () {
                    this.sector.stop();
                    this.sector.scale(1.1, 1.1, this.cx, this.cy);

                    if (this.label) {
                        this.label[0].stop();
                        this.label[0].attr({ r: 7.5 });
                        this.label[1].attr({ "font-weight": 800 });
                    }
                }, function () {
                    this.sector.animate({ transform: 's1 1 ' + this.cx + ' ' + this.cy }, 500, "bounce");

                    if (this.label) {
                        this.label[0].animate({ r: 5 }, 500, "bounce");
                        this.label[1].attr({ "font-weight": 400 });
                    }
                });
    	

		
   
   		/*
    	t=["colors","href","strokewidth"].inject({},
				function(t,n){
				
			         if(e.data(n))
			        	 return t[n]=e.data(n).evalJSON(),t}
		),
		

						 
			        	 i=Raphael(e[0],s,s),
						 
					
			        	 r=i.piechart(s/2,s/2,s*.45,e.data("values").evalJSON(),t);
		                 if(e.data("tooltip"))
		                	 return n={},r.hover(function(){
		                		 return n[this.value]=i.popup(this.mx,this.my,""+this.value.value+" patients ("+Math.round(this.value.value*100/this.total)+"%)")},
		                		 function(){
		                			 return n[this.value].animate({opacity:0},300,function(){return this.remove()
		                				 });
		                				 });
	*/
	
	});
	
	//alert('sdfdsf');
	 
	$j("th.date:[role=columnheader] a span").each(function(){
																			       // alert('dsfdsf');
    	e = $j(this);
    	//e.removeClass("pie-chart-holder");
    	s = getDimensions(e).height;
		
		datatile = e.attr("data-tile");
		
		//alert(datatile);
		
		var r = Raphael(e[0],25,100);
         r.text(12, 50, datatile ).attr({ font: "12px Arial" }).attr({transform: "r" + 270});;
		
	});
	
	
	$j("th.bars div.slant").each(function(){
																			        //alert('dsfdsf');
    	e = $j(this);
    	//e.removeClass("pie-chart-holder");
    	s = getDimensions(e).height;
		
		datatile = e.attr("data-tile");
		
		//alert(datatile);
		
		var r = Raphael(e[0],50,56);
         r.text(12, 25, datatile ).attr({ font: "11px Arial" }).attr({transform: "r" + 300});;
		
	});

});	 