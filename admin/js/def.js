function open_popupWMP (url){
    open_popup( url, 40 ,10, 920, 760, 1);
}

function open_popup(url, posx, posy, size_w, size_h, scroll) {
    if (window.iamIE) {
        msg=window.open(url,'jmd','scrollbars=1,status=0,toolbar=0,directories=0,menubar=0,location=1,resizable=0,width='+size_w+',height='+size_h+', left='+posx+', top='+posy+'');
    } else {
        msg=window.open(url,'jmd','scrollbars='+scroll+',status=0,toolbar=yes,chrome=yes,directories=0,menubar=1,location=0,resizable=0,width='+size_w+',height='+size_h+', left='+posx+', top='+posy+'');
    }
}

_c = function(element)
{
    return $(document.createElement(element));
};

function usort(b,a){var d=[],e="",c=0,g=!1,f={};"string"===typeof a?a=this[a]:"[object Array]"===Object.prototype.toString.call(a)&&(a=this[a[0]][a[1]]);this.php_js=this.php_js||{};this.php_js.ini=this.php_js.ini||{};f=(g=this.php_js.ini["phpjs.strictForIn"]&&this.php_js.ini["phpjs.strictForIn"].local_value&&"off"!==this.php_js.ini["phpjs.strictForIn"].local_value)?b:f;for(e in b)b.hasOwnProperty(e)&&(d.push(b[e]),g&&delete b[e]);try{d.sort(a)}catch(h){return!1}for(c=0;c<d.length;c++)f[c]=d[c];return g|| f};