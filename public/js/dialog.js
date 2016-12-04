
function resize(){
	var o=[];
	var length=0;
	this.add=function(oo){
		length++;
		o.push(oo);
	};
	this.resize=function(){
		for(var i=0;i<length;i++){
			o[i].resize();
		}
	}
}
var resi=new resize();
function dialog(obj,options,callback){
	var th=this;
	var init=false;

	this.init=function(){	
		if(!obj.hasClass("dialog")){
			obj.addClass("dialog");
		}

		var widthobj=500;
		var heightobj=300;

		if(options.width!=null){
			widthobj=options.width;
		}

		if(options.height!=null){
			heightobj=options.height;
		}

		if(options.title==null){
			options.title="Tiêu Đề Dailog";
		}

		var widthw=$(window).width();
		var widthh=$(window).height();

		var left=(widthw-widthobj)/2;
		var top=(widthh-heightobj)/2;
		left=left*100/widthw;
		top=top*100/widthh;

		if(widthobj>widthw){
			widthobj=widthw-10;
			left=1;
		}
		if(heightobj>widthh){
			heightobj=widthh-10;
			top=1;
		}



		
		if(top<=5){
			top=6;
		}

		obj.css({"width":widthobj,"height":heightobj,"left":left+"%","top":(top-5)+"%"});
		obj.find(".ct").css({"height":heightobj-50});

		if(!$(".dimb").length){
			$("body").append("<div class='dimb'></div>");
			$(".dimb").click(function(){
				th.hidedim();
			});
		}
		obj.find(".closedialog").click(function(){
			th.hide();
		});
		if(!init){
			
			init=true;
			resi.add(th);
		}

	};
	this.resize=function(){
		th.init();
	};
	this.show=function(){
		if(callback==null){
			obj.fadeIn();
			$(".dimb").fadeIn();
		}else{
			callback(th,obj,"show");
		}
	};
	this.hide=function(){
		if(callback==null){
			obj.fadeOut();
			$(".dimb").fadeOut();
		}else{
			callback(th,obj,"hide");
		}
	};

	this.hidedim=function(){
			$(".dialog").fadeOut();
			$(".dimb").fadeOut();
	};
	
	this.getObj=function(){
		return obj;
	};
}
$(function(){
	$(window).resize(function(){
		resi.resize();
	});
});