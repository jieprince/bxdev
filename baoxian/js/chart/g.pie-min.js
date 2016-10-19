/*!
 * g.Raphael 0.51 - Charting library, based on Raphaël
 *
 * Copyright (c) 2009-2012 Dmitry Baranovskiy (http://g.raphaeljs.com)
 * Licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) license.
 */
(function(){
	function e(e,t,n,r,i,s){
		function o(e,t,n,r,i){
			var s=Math.PI/180,
			o=e+n*Math.cos(-r*s),
			u=e+n*Math.cos(-i*s),
			a=e+n/2*Math.cos(-(r+(i-r)/2)*s),
			f=t+n*Math.sin(-r*s),
			l=t+n*Math.sin(-i*s),
			s=t+n/2*Math.sin(-(r+(i-r)/2)*s),
			e=["M",e,t,"L",o,f,"A",n,n,0,+(180<Math.abs(i-r)),1,u,l,"z"];
			return e.middle={x:a,y:s},e}
		var s=s||{},u=[],a=e.set(),f=e.set(),l=e.set(),c=i.length,h=0,p=0,d=0,v=s.maxSlices||100,m=parseFloat(s.minPercent)||1,g=Boolean(m);
		f.covers=a;
		if(1==c)
			l.push(e.circle(t,n,r).attr({fill:s.colors&&s.colors[0]||this.colors[0],stroke:s.stroke||"#fff","stroke-width":null==s.strokewidth?1:s.strokewidth})),
			a.push(e.circle(t,n,r).attr(this.shim)),p=i[0],i[0]={value:i[0],order:0,valueOf:function(){
				return this.value}},s.href&&s.href[0]&&a[0].attr({href:s.href[0]}),l[0].middle={x:t,y:n},l[0].mangle=180;
		       else{
		    	   for(var y=0;y<c;y++)
		    		   p+=i[y],i[y]={value:i[y],order:y,valueOf:function(){
		    			   return this.value}};
		    	           i.sort(function(e,t){
		    	        	   return t.value-e.value});for(y=0;y<c;y++)if(g&&100*i[y]/p<m&&(v=y,g=!1),y>v)g=!1,i[v].value+=i[y],i[v].others=!0,d=i[v].value;c=Math.min(v+1,i.length),d&&i.splice(c)&&(i[v].others=!0);for(y=0;y<c;y++){d=h-360*i[y]/p/2,y||(h=90-d,d=h-360*i[y]/p/2);if(s.init)var b=o(t,n,1,h,h-360*i[y]/p).join(",");v=o(t,n,r,h,h-=360*i[y]/p),m=s.matchColors&&1==s.matchColors?i[y].order:y,m=e.path(s.init?b:v).attr({fill:s.colors&&s.colors[m]||this.colors[m]||"#666",stroke:s.stroke||"#fff","stroke-width":null==s.strokewidth?1:s.strokewidth,"stroke-linejoin":"round"}),m.value=i[y],m.middle=v.middle,m.mangle=d,u.push(m),l.push(m),s.init&&m.animate({path:v.join(",")},+s.init-1||1e3,">")}for(y=0;y<c;y++)m=e.path(u[y].attr("path")).attr(this.shim),s.href&&s.href[y]&&m.attr({href:s.href[y]}),m.attr=function(){},a.push(m),l.push(m)}f.hover=function(e,s){for(var s=s||function(){},o=this,u=0;u<c;u++)(function(u,a,f){var l={sector:u,cover:a,cx:t,cy:n,mx:u.middle.x,my:u.middle.y,mangle:u.mangle,r:r,value:i[f],total:p,label:o.labels&&o.labels[f]};a.mouseover(function(){e.call(l)}).mouseout(function(){s.call(l)})})(l[u],a[u],u);return this},f.each=function(e){for(var s=0;s<c;s++){var o=l[s];e.call({sector:o,cover:a[s],cx:t,cy:n,x:o.middle.x,y:o.middle.y,mangle:o.mangle,r:r,value:i[s],total:p,label:this.labels&&this.labels[s]})}return this},f.click=function(e){for(var s=this,o=0;o<c;o++)(function(o,u,a){var f={sector:o,cover:u,cx:t,cy:n,mx:o.middle.x,my:o.middle.y,mangle:o.mangle,r:r,value:i[a],total:p,label:s.labels&&s.labels[a]};u.click(function(){e.call(f)})})(l[o],a[o],o);return this},f.inject=function(e){e.insertBefore(a[0])};if(s.legend){h=s.legend,y=s.legendothers,b=s.legendmark,u=s.legendpos,d=t+r+r/5,v=n+10,h=h||[],u=u&&u.toLowerCase&&u.toLowerCase()||"east",b=e[b&&b.toLowerCase()]||"circle",f.labels=e.set();for(m=0;m<c;m++){var g=l[m].attr("fill"),w=i[m].order;i[m].others&&(h[w]=y||"Others"),h[w]=this.labelise(h[w],i[m],p),f.labels.push(e.set()),f.labels[m].push(e[b](d+5,v,5).attr({fill:g,stroke:"none"})),f.labels[m].push(g=e.text(d+20,v,h[w]||i[w]).attr(this.txtattr).attr({fill:s.legendcolor||"#000","text-anchor":"start"})),a[m].label=f.labels[m],v+=1.2*g.getBBox().height}e=f.labels.getBBox(),f.labels.translate.apply(f.labels,{east:[0,-e.height/2],west:[-e.width-2*r-20,-e.height/2],north:[-r-e.width/2,-r-e.height-10],south:[-r-e.width/2,r+10]}[u]),f.push(f.labels)}return f.push(l,a),f.series=l,f.covers=a,f}var t=function(){};
		t.prototype=Raphael.g,e.prototype=new t,
		Raphael.fn.piechart=function(t,n,r,i,s){return new e(this,t,n,r,i,s)}})();