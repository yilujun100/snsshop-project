/*! jQuery v2.1.4 | (c) 2005, 2015 jQuery Foundation, Inc. | jquery.org/license */
!function(a,b){"object"==typeof module&&"object"==typeof module.exports?module.exports=a.document?b(a,!0):function(a){if(!a.document)throw new Error("jQuery requires a window with a document");return b(a)}:b(a)}("undefined"!=typeof window?window:this,function(a,b){var c=[],d=c.slice,e=c.concat,f=c.push,g=c.indexOf,h={},i=h.toString,j=h.hasOwnProperty,k={},l=a.document,m="2.1.4",n=function(a,b){return new n.fn.init(a,b)},o=/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,p=/^-ms-/,q=/-([\da-z])/gi,r=function(a,b){return b.toUpperCase()};n.fn=n.prototype={jquery:m,constructor:n,selector:"",length:0,toArray:function(){return d.call(this)},get:function(a){return null!=a?0>a?this[a+this.length]:this[a]:d.call(this)},pushStack:function(a){var b=n.merge(this.constructor(),a);return b.prevObject=this,b.context=this.context,b},each:function(a,b){return n.each(this,a,b)},map:function(a){return this.pushStack(n.map(this,function(b,c){return a.call(b,c,b)}))},slice:function(){return this.pushStack(d.apply(this,arguments))},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},eq:function(a){var b=this.length,c=+a+(0>a?b:0);return this.pushStack(c>=0&&b>c?[this[c]]:[])},end:function(){return this.prevObject||this.constructor(null)},push:f,sort:c.sort,splice:c.splice},n.extend=n.fn.extend=function(){var a,b,c,d,e,f,g=arguments[0]||{},h=1,i=arguments.length,j=!1;for("boolean"==typeof g&&(j=g,g=arguments[h]||{},h++),"object"==typeof g||n.isFunction(g)||(g={}),h===i&&(g=this,h--);i>h;h++)if(null!=(a=arguments[h]))for(b in a)c=g[b],d=a[b],g!==d&&(j&&d&&(n.isPlainObject(d)||(e=n.isArray(d)))?(e?(e=!1,f=c&&n.isArray(c)?c:[]):f=c&&n.isPlainObject(c)?c:{},g[b]=n.extend(j,f,d)):void 0!==d&&(g[b]=d));return g},n.extend({expando:"jQuery"+(m+Math.random()).replace(/\D/g,""),isReady:!0,error:function(a){throw new Error(a)},noop:function(){},isFunction:function(a){return"function"===n.type(a)},isArray:Array.isArray,isWindow:function(a){return null!=a&&a===a.window},isNumeric:function(a){return!n.isArray(a)&&a-parseFloat(a)+1>=0},isPlainObject:function(a){return"object"!==n.type(a)||a.nodeType||n.isWindow(a)?!1:a.constructor&&!j.call(a.constructor.prototype,"isPrototypeOf")?!1:!0},isEmptyObject:function(a){var b;for(b in a)return!1;return!0},type:function(a){return null==a?a+"":"object"==typeof a||"function"==typeof a?h[i.call(a)]||"object":typeof a},globalEval:function(a){var b,c=eval;a=n.trim(a),a&&(1===a.indexOf("use strict")?(b=l.createElement("script"),b.text=a,l.head.appendChild(b).parentNode.removeChild(b)):c(a))},camelCase:function(a){return a.replace(p,"ms-").replace(q,r)},nodeName:function(a,b){return a.nodeName&&a.nodeName.toLowerCase()===b.toLowerCase()},each:function(a,b,c){var d,e=0,f=a.length,g=s(a);if(c){if(g){for(;f>e;e++)if(d=b.apply(a[e],c),d===!1)break}else for(e in a)if(d=b.apply(a[e],c),d===!1)break}else if(g){for(;f>e;e++)if(d=b.call(a[e],e,a[e]),d===!1)break}else for(e in a)if(d=b.call(a[e],e,a[e]),d===!1)break;return a},trim:function(a){return null==a?"":(a+"").replace(o,"")},makeArray:function(a,b){var c=b||[];return null!=a&&(s(Object(a))?n.merge(c,"string"==typeof a?[a]:a):f.call(c,a)),c},inArray:function(a,b,c){return null==b?-1:g.call(b,a,c)},merge:function(a,b){for(var c=+b.length,d=0,e=a.length;c>d;d++)a[e++]=b[d];return a.length=e,a},grep:function(a,b,c){for(var d,e=[],f=0,g=a.length,h=!c;g>f;f++)d=!b(a[f],f),d!==h&&e.push(a[f]);return e},map:function(a,b,c){var d,f=0,g=a.length,h=s(a),i=[];if(h)for(;g>f;f++)d=b(a[f],f,c),null!=d&&i.push(d);else for(f in a)d=b(a[f],f,c),null!=d&&i.push(d);return e.apply([],i)},guid:1,proxy:function(a,b){var c,e,f;return"string"==typeof b&&(c=a[b],b=a,a=c),n.isFunction(a)?(e=d.call(arguments,2),f=function(){return a.apply(b||this,e.concat(d.call(arguments)))},f.guid=a.guid=a.guid||n.guid++,f):void 0},now:Date.now,support:k}),n.each("Boolean Number String Function Array Date RegExp Object Error".split(" "),function(a,b){h["[object "+b+"]"]=b.toLowerCase()});function s(a){var b="length"in a&&a.length,c=n.type(a);return"function"===c||n.isWindow(a)?!1:1===a.nodeType&&b?!0:"array"===c||0===b||"number"==typeof b&&b>0&&b-1 in a}var t=function(a){var b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u="sizzle"+1*new Date,v=a.document,w=0,x=0,y=ha(),z=ha(),A=ha(),B=function(a,b){return a===b&&(l=!0),0},C=1<<31,D={}.hasOwnProperty,E=[],F=E.pop,G=E.push,H=E.push,I=E.slice,J=function(a,b){for(var c=0,d=a.length;d>c;c++)if(a[c]===b)return c;return-1},K="checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",L="[\\x20\\t\\r\\n\\f]",M="(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",N=M.replace("w","w#"),O="\\["+L+"*("+M+")(?:"+L+"*([*^$|!~]?=)"+L+"*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|("+N+"))|)"+L+"*\\]",P=":("+M+")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|"+O+")*)|.*)\\)|)",Q=new RegExp(L+"+","g"),R=new RegExp("^"+L+"+|((?:^|[^\\\\])(?:\\\\.)*)"+L+"+$","g"),S=new RegExp("^"+L+"*,"+L+"*"),T=new RegExp("^"+L+"*([>+~]|"+L+")"+L+"*"),U=new RegExp("="+L+"*([^\\]'\"]*?)"+L+"*\\]","g"),V=new RegExp(P),W=new RegExp("^"+N+"$"),X={ID:new RegExp("^#("+M+")"),CLASS:new RegExp("^\\.("+M+")"),TAG:new RegExp("^("+M.replace("w","w*")+")"),ATTR:new RegExp("^"+O),PSEUDO:new RegExp("^"+P),CHILD:new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\("+L+"*(even|odd|(([+-]|)(\\d*)n|)"+L+"*(?:([+-]|)"+L+"*(\\d+)|))"+L+"*\\)|)","i"),bool:new RegExp("^(?:"+K+")$","i"),needsContext:new RegExp("^"+L+"*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\("+L+"*((?:-\\d)?\\d*)"+L+"*\\)|)(?=[^-]|$)","i")},Y=/^(?:input|select|textarea|button)$/i,Z=/^h\d$/i,$=/^[^{]+\{\s*\[native \w/,_=/^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,aa=/[+~]/,ba=/'|\\/g,ca=new RegExp("\\\\([\\da-f]{1,6}"+L+"?|("+L+")|.)","ig"),da=function(a,b,c){var d="0x"+b-65536;return d!==d||c?b:0>d?String.fromCharCode(d+65536):String.fromCharCode(d>>10|55296,1023&d|56320)},ea=function(){m()};try{H.apply(E=I.call(v.childNodes),v.childNodes),E[v.childNodes.length].nodeType}catch(fa){H={apply:E.length?function(a,b){G.apply(a,I.call(b))}:function(a,b){var c=a.length,d=0;while(a[c++]=b[d++]);a.length=c-1}}}function ga(a,b,d,e){var f,h,j,k,l,o,r,s,w,x;if((b?b.ownerDocument||b:v)!==n&&m(b),b=b||n,d=d||[],k=b.nodeType,"string"!=typeof a||!a||1!==k&&9!==k&&11!==k)return d;if(!e&&p){if(11!==k&&(f=_.exec(a)))if(j=f[1]){if(9===k){if(h=b.getElementById(j),!h||!h.parentNode)return d;if(h.id===j)return d.push(h),d}else if(b.ownerDocument&&(h=b.ownerDocument.getElementById(j))&&t(b,h)&&h.id===j)return d.push(h),d}else{if(f[2])return H.apply(d,b.getElementsByTagName(a)),d;if((j=f[3])&&c.getElementsByClassName)return H.apply(d,b.getElementsByClassName(j)),d}if(c.qsa&&(!q||!q.test(a))){if(s=r=u,w=b,x=1!==k&&a,1===k&&"object"!==b.nodeName.toLowerCase()){o=g(a),(r=b.getAttribute("id"))?s=r.replace(ba,"\\$&"):b.setAttribute("id",s),s="[id='"+s+"'] ",l=o.length;while(l--)o[l]=s+ra(o[l]);w=aa.test(a)&&pa(b.parentNode)||b,x=o.join(",")}if(x)try{return H.apply(d,w.querySelectorAll(x)),d}catch(y){}finally{r||b.removeAttribute("id")}}}return i(a.replace(R,"$1"),b,d,e)}function ha(){var a=[];function b(c,e){return a.push(c+" ")>d.cacheLength&&delete b[a.shift()],b[c+" "]=e}return b}function ia(a){return a[u]=!0,a}function ja(a){var b=n.createElement("div");try{return!!a(b)}catch(c){return!1}finally{b.parentNode&&b.parentNode.removeChild(b),b=null}}function ka(a,b){var c=a.split("|"),e=a.length;while(e--)d.attrHandle[c[e]]=b}function la(a,b){var c=b&&a,d=c&&1===a.nodeType&&1===b.nodeType&&(~b.sourceIndex||C)-(~a.sourceIndex||C);if(d)return d;if(c)while(c=c.nextSibling)if(c===b)return-1;return a?1:-1}function ma(a){return function(b){var c=b.nodeName.toLowerCase();return"input"===c&&b.type===a}}function na(a){return function(b){var c=b.nodeName.toLowerCase();return("input"===c||"button"===c)&&b.type===a}}function oa(a){return ia(function(b){return b=+b,ia(function(c,d){var e,f=a([],c.length,b),g=f.length;while(g--)c[e=f[g]]&&(c[e]=!(d[e]=c[e]))})})}function pa(a){return a&&"undefined"!=typeof a.getElementsByTagName&&a}c=ga.support={},f=ga.isXML=function(a){var b=a&&(a.ownerDocument||a).documentElement;return b?"HTML"!==b.nodeName:!1},m=ga.setDocument=function(a){var b,e,g=a?a.ownerDocument||a:v;return g!==n&&9===g.nodeType&&g.documentElement?(n=g,o=g.documentElement,e=g.defaultView,e&&e!==e.top&&(e.addEventListener?e.addEventListener("unload",ea,!1):e.attachEvent&&e.attachEvent("onunload",ea)),p=!f(g),c.attributes=ja(function(a){return a.className="i",!a.getAttribute("className")}),c.getElementsByTagName=ja(function(a){return a.appendChild(g.createComment("")),!a.getElementsByTagName("*").length}),c.getElementsByClassName=$.test(g.getElementsByClassName),c.getById=ja(function(a){return o.appendChild(a).id=u,!g.getElementsByName||!g.getElementsByName(u).length}),c.getById?(d.find.ID=function(a,b){if("undefined"!=typeof b.getElementById&&p){var c=b.getElementById(a);return c&&c.parentNode?[c]:[]}},d.filter.ID=function(a){var b=a.replace(ca,da);return function(a){return a.getAttribute("id")===b}}):(delete d.find.ID,d.filter.ID=function(a){var b=a.replace(ca,da);return function(a){var c="undefined"!=typeof a.getAttributeNode&&a.getAttributeNode("id");return c&&c.value===b}}),d.find.TAG=c.getElementsByTagName?function(a,b){return"undefined"!=typeof b.getElementsByTagName?b.getElementsByTagName(a):c.qsa?b.querySelectorAll(a):void 0}:function(a,b){var c,d=[],e=0,f=b.getElementsByTagName(a);if("*"===a){while(c=f[e++])1===c.nodeType&&d.push(c);return d}return f},d.find.CLASS=c.getElementsByClassName&&function(a,b){return p?b.getElementsByClassName(a):void 0},r=[],q=[],(c.qsa=$.test(g.querySelectorAll))&&(ja(function(a){o.appendChild(a).innerHTML="<a id='"+u+"'></a><select id='"+u+"-\f]' msallowcapture=''><option selected=''></option></select>",a.querySelectorAll("[msallowcapture^='']").length&&q.push("[*^$]="+L+"*(?:''|\"\")"),a.querySelectorAll("[selected]").length||q.push("\\["+L+"*(?:value|"+K+")"),a.querySelectorAll("[id~="+u+"-]").length||q.push("~="),a.querySelectorAll(":checked").length||q.push(":checked"),a.querySelectorAll("a#"+u+"+*").length||q.push(".#.+[+~]")}),ja(function(a){var b=g.createElement("input");b.setAttribute("type","hidden"),a.appendChild(b).setAttribute("name","D"),a.querySelectorAll("[name=d]").length&&q.push("name"+L+"*[*^$|!~]?="),a.querySelectorAll(":enabled").length||q.push(":enabled",":disabled"),a.querySelectorAll("*,:x"),q.push(",.*:")})),(c.matchesSelector=$.test(s=o.matches||o.webkitMatchesSelector||o.mozMatchesSelector||o.oMatchesSelector||o.msMatchesSelector))&&ja(function(a){c.disconnectedMatch=s.call(a,"div"),s.call(a,"[s!='']:x"),r.push("!=",P)}),q=q.length&&new RegExp(q.join("|")),r=r.length&&new RegExp(r.join("|")),b=$.test(o.compareDocumentPosition),t=b||$.test(o.contains)?function(a,b){var c=9===a.nodeType?a.documentElement:a,d=b&&b.parentNode;return a===d||!(!d||1!==d.nodeType||!(c.contains?c.contains(d):a.compareDocumentPosition&&16&a.compareDocumentPosition(d)))}:function(a,b){if(b)while(b=b.parentNode)if(b===a)return!0;return!1},B=b?function(a,b){if(a===b)return l=!0,0;var d=!a.compareDocumentPosition-!b.compareDocumentPosition;return d?d:(d=(a.ownerDocument||a)===(b.ownerDocument||b)?a.compareDocumentPosition(b):1,1&d||!c.sortDetached&&b.compareDocumentPosition(a)===d?a===g||a.ownerDocument===v&&t(v,a)?-1:b===g||b.ownerDocument===v&&t(v,b)?1:k?J(k,a)-J(k,b):0:4&d?-1:1)}:function(a,b){if(a===b)return l=!0,0;var c,d=0,e=a.parentNode,f=b.parentNode,h=[a],i=[b];if(!e||!f)return a===g?-1:b===g?1:e?-1:f?1:k?J(k,a)-J(k,b):0;if(e===f)return la(a,b);c=a;while(c=c.parentNode)h.unshift(c);c=b;while(c=c.parentNode)i.unshift(c);while(h[d]===i[d])d++;return d?la(h[d],i[d]):h[d]===v?-1:i[d]===v?1:0},g):n},ga.matches=function(a,b){return ga(a,null,null,b)},ga.matchesSelector=function(a,b){if((a.ownerDocument||a)!==n&&m(a),b=b.replace(U,"='$1']"),!(!c.matchesSelector||!p||r&&r.test(b)||q&&q.test(b)))try{var d=s.call(a,b);if(d||c.disconnectedMatch||a.document&&11!==a.document.nodeType)return d}catch(e){}return ga(b,n,null,[a]).length>0},ga.contains=function(a,b){return(a.ownerDocument||a)!==n&&m(a),t(a,b)},ga.attr=function(a,b){(a.ownerDocument||a)!==n&&m(a);var e=d.attrHandle[b.toLowerCase()],f=e&&D.call(d.attrHandle,b.toLowerCase())?e(a,b,!p):void 0;return void 0!==f?f:c.attributes||!p?a.getAttribute(b):(f=a.getAttributeNode(b))&&f.specified?f.value:null},ga.error=function(a){throw new Error("Syntax error, unrecognized expression: "+a)},ga.uniqueSort=function(a){var b,d=[],e=0,f=0;if(l=!c.detectDuplicates,k=!c.sortStable&&a.slice(0),a.sort(B),l){while(b=a[f++])b===a[f]&&(e=d.push(f));while(e--)a.splice(d[e],1)}return k=null,a},e=ga.getText=function(a){var b,c="",d=0,f=a.nodeType;if(f){if(1===f||9===f||11===f){if("string"==typeof a.textContent)return a.textContent;for(a=a.firstChild;a;a=a.nextSibling)c+=e(a)}else if(3===f||4===f)return a.nodeValue}else while(b=a[d++])c+=e(b);return c},d=ga.selectors={cacheLength:50,createPseudo:ia,match:X,attrHandle:{},find:{},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(a){return a[1]=a[1].replace(ca,da),a[3]=(a[3]||a[4]||a[5]||"").replace(ca,da),"~="===a[2]&&(a[3]=" "+a[3]+" "),a.slice(0,4)},CHILD:function(a){return a[1]=a[1].toLowerCase(),"nth"===a[1].slice(0,3)?(a[3]||ga.error(a[0]),a[4]=+(a[4]?a[5]+(a[6]||1):2*("even"===a[3]||"odd"===a[3])),a[5]=+(a[7]+a[8]||"odd"===a[3])):a[3]&&ga.error(a[0]),a},PSEUDO:function(a){var b,c=!a[6]&&a[2];return X.CHILD.test(a[0])?null:(a[3]?a[2]=a[4]||a[5]||"":c&&V.test(c)&&(b=g(c,!0))&&(b=c.indexOf(")",c.length-b)-c.length)&&(a[0]=a[0].slice(0,b),a[2]=c.slice(0,b)),a.slice(0,3))}},filter:{TAG:function(a){var b=a.replace(ca,da).toLowerCase();return"*"===a?function(){return!0}:function(a){return a.nodeName&&a.nodeName.toLowerCase()===b}},CLASS:function(a){var b=y[a+" "];return b||(b=new RegExp("(^|"+L+")"+a+"("+L+"|$)"))&&y(a,function(a){return b.test("string"==typeof a.className&&a.className||"undefined"!=typeof a.getAttribute&&a.getAttribute("class")||"")})},ATTR:function(a,b,c){return function(d){var e=ga.attr(d,a);return null==e?"!="===b:b?(e+="","="===b?e===c:"!="===b?e!==c:"^="===b?c&&0===e.indexOf(c):"*="===b?c&&e.indexOf(c)>-1:"$="===b?c&&e.slice(-c.length)===c:"~="===b?(" "+e.replace(Q," ")+" ").indexOf(c)>-1:"|="===b?e===c||e.slice(0,c.length+1)===c+"-":!1):!0}},CHILD:function(a,b,c,d,e){var f="nth"!==a.slice(0,3),g="last"!==a.slice(-4),h="of-type"===b;return 1===d&&0===e?function(a){return!!a.parentNode}:function(b,c,i){var j,k,l,m,n,o,p=f!==g?"nextSibling":"previousSibling",q=b.parentNode,r=h&&b.nodeName.toLowerCase(),s=!i&&!h;if(q){if(f){while(p){l=b;while(l=l[p])if(h?l.nodeName.toLowerCase()===r:1===l.nodeType)return!1;o=p="only"===a&&!o&&"nextSibling"}return!0}if(o=[g?q.firstChild:q.lastChild],g&&s){k=q[u]||(q[u]={}),j=k[a]||[],n=j[0]===w&&j[1],m=j[0]===w&&j[2],l=n&&q.childNodes[n];while(l=++n&&l&&l[p]||(m=n=0)||o.pop())if(1===l.nodeType&&++m&&l===b){k[a]=[w,n,m];break}}else if(s&&(j=(b[u]||(b[u]={}))[a])&&j[0]===w)m=j[1];else while(l=++n&&l&&l[p]||(m=n=0)||o.pop())if((h?l.nodeName.toLowerCase()===r:1===l.nodeType)&&++m&&(s&&((l[u]||(l[u]={}))[a]=[w,m]),l===b))break;return m-=e,m===d||m%d===0&&m/d>=0}}},PSEUDO:function(a,b){var c,e=d.pseudos[a]||d.setFilters[a.toLowerCase()]||ga.error("unsupported pseudo: "+a);return e[u]?e(b):e.length>1?(c=[a,a,"",b],d.setFilters.hasOwnProperty(a.toLowerCase())?ia(function(a,c){var d,f=e(a,b),g=f.length;while(g--)d=J(a,f[g]),a[d]=!(c[d]=f[g])}):function(a){return e(a,0,c)}):e}},pseudos:{not:ia(function(a){var b=[],c=[],d=h(a.replace(R,"$1"));return d[u]?ia(function(a,b,c,e){var f,g=d(a,null,e,[]),h=a.length;while(h--)(f=g[h])&&(a[h]=!(b[h]=f))}):function(a,e,f){return b[0]=a,d(b,null,f,c),b[0]=null,!c.pop()}}),has:ia(function(a){return function(b){return ga(a,b).length>0}}),contains:ia(function(a){return a=a.replace(ca,da),function(b){return(b.textContent||b.innerText||e(b)).indexOf(a)>-1}}),lang:ia(function(a){return W.test(a||"")||ga.error("unsupported lang: "+a),a=a.replace(ca,da).toLowerCase(),function(b){var c;do if(c=p?b.lang:b.getAttribute("xml:lang")||b.getAttribute("lang"))return c=c.toLowerCase(),c===a||0===c.indexOf(a+"-");while((b=b.parentNode)&&1===b.nodeType);return!1}}),target:function(b){var c=a.location&&a.location.hash;return c&&c.slice(1)===b.id},root:function(a){return a===o},focus:function(a){return a===n.activeElement&&(!n.hasFocus||n.hasFocus())&&!!(a.type||a.href||~a.tabIndex)},enabled:function(a){return a.disabled===!1},disabled:function(a){return a.disabled===!0},checked:function(a){var b=a.nodeName.toLowerCase();return"input"===b&&!!a.checked||"option"===b&&!!a.selected},selected:function(a){return a.parentNode&&a.parentNode.selectedIndex,a.selected===!0},empty:function(a){for(a=a.firstChild;a;a=a.nextSibling)if(a.nodeType<6)return!1;return!0},parent:function(a){return!d.pseudos.empty(a)},header:function(a){return Z.test(a.nodeName)},input:function(a){return Y.test(a.nodeName)},button:function(a){var b=a.nodeName.toLowerCase();return"input"===b&&"button"===a.type||"button"===b},text:function(a){var b;return"input"===a.nodeName.toLowerCase()&&"text"===a.type&&(null==(b=a.getAttribute("type"))||"text"===b.toLowerCase())},first:oa(function(){return[0]}),last:oa(function(a,b){return[b-1]}),eq:oa(function(a,b,c){return[0>c?c+b:c]}),even:oa(function(a,b){for(var c=0;b>c;c+=2)a.push(c);return a}),odd:oa(function(a,b){for(var c=1;b>c;c+=2)a.push(c);return a}),lt:oa(function(a,b,c){for(var d=0>c?c+b:c;--d>=0;)a.push(d);return a}),gt:oa(function(a,b,c){for(var d=0>c?c+b:c;++d<b;)a.push(d);return a})}},d.pseudos.nth=d.pseudos.eq;for(b in{radio:!0,checkbox:!0,file:!0,password:!0,image:!0})d.pseudos[b]=ma(b);for(b in{submit:!0,reset:!0})d.pseudos[b]=na(b);function qa(){}qa.prototype=d.filters=d.pseudos,d.setFilters=new qa,g=ga.tokenize=function(a,b){var c,e,f,g,h,i,j,k=z[a+" "];if(k)return b?0:k.slice(0);h=a,i=[],j=d.preFilter;while(h){(!c||(e=S.exec(h)))&&(e&&(h=h.slice(e[0].length)||h),i.push(f=[])),c=!1,(e=T.exec(h))&&(c=e.shift(),f.push({value:c,type:e[0].replace(R," ")}),h=h.slice(c.length));for(g in d.filter)!(e=X[g].exec(h))||j[g]&&!(e=j[g](e))||(c=e.shift(),f.push({value:c,type:g,matches:e}),h=h.slice(c.length));if(!c)break}return b?h.length:h?ga.error(a):z(a,i).slice(0)};function ra(a){for(var b=0,c=a.length,d="";c>b;b++)d+=a[b].value;return d}function sa(a,b,c){var d=b.dir,e=c&&"parentNode"===d,f=x++;return b.first?function(b,c,f){while(b=b[d])if(1===b.nodeType||e)return a(b,c,f)}:function(b,c,g){var h,i,j=[w,f];if(g){while(b=b[d])if((1===b.nodeType||e)&&a(b,c,g))return!0}else while(b=b[d])if(1===b.nodeType||e){if(i=b[u]||(b[u]={}),(h=i[d])&&h[0]===w&&h[1]===f)return j[2]=h[2];if(i[d]=j,j[2]=a(b,c,g))return!0}}}function ta(a){return a.length>1?function(b,c,d){var e=a.length;while(e--)if(!a[e](b,c,d))return!1;return!0}:a[0]}function ua(a,b,c){for(var d=0,e=b.length;e>d;d++)ga(a,b[d],c);return c}function va(a,b,c,d,e){for(var f,g=[],h=0,i=a.length,j=null!=b;i>h;h++)(f=a[h])&&(!c||c(f,d,e))&&(g.push(f),j&&b.push(h));return g}function wa(a,b,c,d,e,f){return d&&!d[u]&&(d=wa(d)),e&&!e[u]&&(e=wa(e,f)),ia(function(f,g,h,i){var j,k,l,m=[],n=[],o=g.length,p=f||ua(b||"*",h.nodeType?[h]:h,[]),q=!a||!f&&b?p:va(p,m,a,h,i),r=c?e||(f?a:o||d)?[]:g:q;if(c&&c(q,r,h,i),d){j=va(r,n),d(j,[],h,i),k=j.length;while(k--)(l=j[k])&&(r[n[k]]=!(q[n[k]]=l))}if(f){if(e||a){if(e){j=[],k=r.length;while(k--)(l=r[k])&&j.push(q[k]=l);e(null,r=[],j,i)}k=r.length;while(k--)(l=r[k])&&(j=e?J(f,l):m[k])>-1&&(f[j]=!(g[j]=l))}}else r=va(r===g?r.splice(o,r.length):r),e?e(null,g,r,i):H.apply(g,r)})}function xa(a){for(var b,c,e,f=a.length,g=d.relative[a[0].type],h=g||d.relative[" "],i=g?1:0,k=sa(function(a){return a===b},h,!0),l=sa(function(a){return J(b,a)>-1},h,!0),m=[function(a,c,d){var e=!g&&(d||c!==j)||((b=c).nodeType?k(a,c,d):l(a,c,d));return b=null,e}];f>i;i++)if(c=d.relative[a[i].type])m=[sa(ta(m),c)];else{if(c=d.filter[a[i].type].apply(null,a[i].matches),c[u]){for(e=++i;f>e;e++)if(d.relative[a[e].type])break;return wa(i>1&&ta(m),i>1&&ra(a.slice(0,i-1).concat({value:" "===a[i-2].type?"*":""})).replace(R,"$1"),c,e>i&&xa(a.slice(i,e)),f>e&&xa(a=a.slice(e)),f>e&&ra(a))}m.push(c)}return ta(m)}function ya(a,b){var c=b.length>0,e=a.length>0,f=function(f,g,h,i,k){var l,m,o,p=0,q="0",r=f&&[],s=[],t=j,u=f||e&&d.find.TAG("*",k),v=w+=null==t?1:Math.random()||.1,x=u.length;for(k&&(j=g!==n&&g);q!==x&&null!=(l=u[q]);q++){if(e&&l){m=0;while(o=a[m++])if(o(l,g,h)){i.push(l);break}k&&(w=v)}c&&((l=!o&&l)&&p--,f&&r.push(l))}if(p+=q,c&&q!==p){m=0;while(o=b[m++])o(r,s,g,h);if(f){if(p>0)while(q--)r[q]||s[q]||(s[q]=F.call(i));s=va(s)}H.apply(i,s),k&&!f&&s.length>0&&p+b.length>1&&ga.uniqueSort(i)}return k&&(w=v,j=t),r};return c?ia(f):f}return h=ga.compile=function(a,b){var c,d=[],e=[],f=A[a+" "];if(!f){b||(b=g(a)),c=b.length;while(c--)f=xa(b[c]),f[u]?d.push(f):e.push(f);f=A(a,ya(e,d)),f.selector=a}return f},i=ga.select=function(a,b,e,f){var i,j,k,l,m,n="function"==typeof a&&a,o=!f&&g(a=n.selector||a);if(e=e||[],1===o.length){if(j=o[0]=o[0].slice(0),j.length>2&&"ID"===(k=j[0]).type&&c.getById&&9===b.nodeType&&p&&d.relative[j[1].type]){if(b=(d.find.ID(k.matches[0].replace(ca,da),b)||[])[0],!b)return e;n&&(b=b.parentNode),a=a.slice(j.shift().value.length)}i=X.needsContext.test(a)?0:j.length;while(i--){if(k=j[i],d.relative[l=k.type])break;if((m=d.find[l])&&(f=m(k.matches[0].replace(ca,da),aa.test(j[0].type)&&pa(b.parentNode)||b))){if(j.splice(i,1),a=f.length&&ra(j),!a)return H.apply(e,f),e;break}}}return(n||h(a,o))(f,b,!p,e,aa.test(a)&&pa(b.parentNode)||b),e},c.sortStable=u.split("").sort(B).join("")===u,c.detectDuplicates=!!l,m(),c.sortDetached=ja(function(a){return 1&a.compareDocumentPosition(n.createElement("div"))}),ja(function(a){return a.innerHTML="<a href='#'></a>","#"===a.firstChild.getAttribute("href")})||ka("type|href|height|width",function(a,b,c){return c?void 0:a.getAttribute(b,"type"===b.toLowerCase()?1:2)}),c.attributes&&ja(function(a){return a.innerHTML="<input/>",a.firstChild.setAttribute("value",""),""===a.firstChild.getAttribute("value")})||ka("value",function(a,b,c){return c||"input"!==a.nodeName.toLowerCase()?void 0:a.defaultValue}),ja(function(a){return null==a.getAttribute("disabled")})||ka(K,function(a,b,c){var d;return c?void 0:a[b]===!0?b.toLowerCase():(d=a.getAttributeNode(b))&&d.specified?d.value:null}),ga}(a);n.find=t,n.expr=t.selectors,n.expr[":"]=n.expr.pseudos,n.unique=t.uniqueSort,n.text=t.getText,n.isXMLDoc=t.isXML,n.contains=t.contains;var u=n.expr.match.needsContext,v=/^<(\w+)\s*\/?>(?:<\/\1>|)$/,w=/^.[^:#\[\.,]*$/;function x(a,b,c){if(n.isFunction(b))return n.grep(a,function(a,d){return!!b.call(a,d,a)!==c});if(b.nodeType)return n.grep(a,function(a){return a===b!==c});if("string"==typeof b){if(w.test(b))return n.filter(b,a,c);b=n.filter(b,a)}return n.grep(a,function(a){return g.call(b,a)>=0!==c})}n.filter=function(a,b,c){var d=b[0];return c&&(a=":not("+a+")"),1===b.length&&1===d.nodeType?n.find.matchesSelector(d,a)?[d]:[]:n.find.matches(a,n.grep(b,function(a){return 1===a.nodeType}))},n.fn.extend({find:function(a){var b,c=this.length,d=[],e=this;if("string"!=typeof a)return this.pushStack(n(a).filter(function(){for(b=0;c>b;b++)if(n.contains(e[b],this))return!0}));for(b=0;c>b;b++)n.find(a,e[b],d);return d=this.pushStack(c>1?n.unique(d):d),d.selector=this.selector?this.selector+" "+a:a,d},filter:function(a){return this.pushStack(x(this,a||[],!1))},not:function(a){return this.pushStack(x(this,a||[],!0))},is:function(a){return!!x(this,"string"==typeof a&&u.test(a)?n(a):a||[],!1).length}});var y,z=/^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/,A=n.fn.init=function(a,b){var c,d;if(!a)return this;if("string"==typeof a){if(c="<"===a[0]&&">"===a[a.length-1]&&a.length>=3?[null,a,null]:z.exec(a),!c||!c[1]&&b)return!b||b.jquery?(b||y).find(a):this.constructor(b).find(a);if(c[1]){if(b=b instanceof n?b[0]:b,n.merge(this,n.parseHTML(c[1],b&&b.nodeType?b.ownerDocument||b:l,!0)),v.test(c[1])&&n.isPlainObject(b))for(c in b)n.isFunction(this[c])?this[c](b[c]):this.attr(c,b[c]);return this}return d=l.getElementById(c[2]),d&&d.parentNode&&(this.length=1,this[0]=d),this.context=l,this.selector=a,this}return a.nodeType?(this.context=this[0]=a,this.length=1,this):n.isFunction(a)?"undefined"!=typeof y.ready?y.ready(a):a(n):(void 0!==a.selector&&(this.selector=a.selector,this.context=a.context),n.makeArray(a,this))};A.prototype=n.fn,y=n(l);var B=/^(?:parents|prev(?:Until|All))/,C={children:!0,contents:!0,next:!0,prev:!0};n.extend({dir:function(a,b,c){var d=[],e=void 0!==c;while((a=a[b])&&9!==a.nodeType)if(1===a.nodeType){if(e&&n(a).is(c))break;d.push(a)}return d},sibling:function(a,b){for(var c=[];a;a=a.nextSibling)1===a.nodeType&&a!==b&&c.push(a);return c}}),n.fn.extend({has:function(a){var b=n(a,this),c=b.length;return this.filter(function(){for(var a=0;c>a;a++)if(n.contains(this,b[a]))return!0})},closest:function(a,b){for(var c,d=0,e=this.length,f=[],g=u.test(a)||"string"!=typeof a?n(a,b||this.context):0;e>d;d++)for(c=this[d];c&&c!==b;c=c.parentNode)if(c.nodeType<11&&(g?g.index(c)>-1:1===c.nodeType&&n.find.matchesSelector(c,a))){f.push(c);break}return this.pushStack(f.length>1?n.unique(f):f)},index:function(a){return a?"string"==typeof a?g.call(n(a),this[0]):g.call(this,a.jquery?a[0]:a):this[0]&&this[0].parentNode?this.first().prevAll().length:-1},add:function(a,b){return this.pushStack(n.unique(n.merge(this.get(),n(a,b))))},addBack:function(a){return this.add(null==a?this.prevObject:this.prevObject.filter(a))}});function D(a,b){while((a=a[b])&&1!==a.nodeType);return a}n.each({parent:function(a){var b=a.parentNode;return b&&11!==b.nodeType?b:null},parents:function(a){return n.dir(a,"parentNode")},parentsUntil:function(a,b,c){return n.dir(a,"parentNode",c)},next:function(a){return D(a,"nextSibling")},prev:function(a){return D(a,"previousSibling")},nextAll:function(a){return n.dir(a,"nextSibling")},prevAll:function(a){return n.dir(a,"previousSibling")},nextUntil:function(a,b,c){return n.dir(a,"nextSibling",c)},prevUntil:function(a,b,c){return n.dir(a,"previousSibling",c)},siblings:function(a){return n.sibling((a.parentNode||{}).firstChild,a)},children:function(a){return n.sibling(a.firstChild)},contents:function(a){return a.contentDocument||n.merge([],a.childNodes)}},function(a,b){n.fn[a]=function(c,d){var e=n.map(this,b,c);return"Until"!==a.slice(-5)&&(d=c),d&&"string"==typeof d&&(e=n.filter(d,e)),this.length>1&&(C[a]||n.unique(e),B.test(a)&&e.reverse()),this.pushStack(e)}});var E=/\S+/g,F={};function G(a){var b=F[a]={};return n.each(a.match(E)||[],function(a,c){b[c]=!0}),b}n.Callbacks=function(a){a="string"==typeof a?F[a]||G(a):n.extend({},a);var b,c,d,e,f,g,h=[],i=!a.once&&[],j=function(l){for(b=a.memory&&l,c=!0,g=e||0,e=0,f=h.length,d=!0;h&&f>g;g++)if(h[g].apply(l[0],l[1])===!1&&a.stopOnFalse){b=!1;break}d=!1,h&&(i?i.length&&j(i.shift()):b?h=[]:k.disable())},k={add:function(){if(h){var c=h.length;!function g(b){n.each(b,function(b,c){var d=n.type(c);"function"===d?a.unique&&k.has(c)||h.push(c):c&&c.length&&"string"!==d&&g(c)})}(arguments),d?f=h.length:b&&(e=c,j(b))}return this},remove:function(){return h&&n.each(arguments,function(a,b){var c;while((c=n.inArray(b,h,c))>-1)h.splice(c,1),d&&(f>=c&&f--,g>=c&&g--)}),this},has:function(a){return a?n.inArray(a,h)>-1:!(!h||!h.length)},empty:function(){return h=[],f=0,this},disable:function(){return h=i=b=void 0,this},disabled:function(){return!h},lock:function(){return i=void 0,b||k.disable(),this},locked:function(){return!i},fireWith:function(a,b){return!h||c&&!i||(b=b||[],b=[a,b.slice?b.slice():b],d?i.push(b):j(b)),this},fire:function(){return k.fireWith(this,arguments),this},fired:function(){return!!c}};return k},n.extend({Deferred:function(a){var b=[["resolve","done",n.Callbacks("once memory"),"resolved"],["reject","fail",n.Callbacks("once memory"),"rejected"],["notify","progress",n.Callbacks("memory")]],c="pending",d={state:function(){return c},always:function(){return e.done(arguments).fail(arguments),this},then:function(){var a=arguments;return n.Deferred(function(c){n.each(b,function(b,f){var g=n.isFunction(a[b])&&a[b];e[f[1]](function(){var a=g&&g.apply(this,arguments);a&&n.isFunction(a.promise)?a.promise().done(c.resolve).fail(c.reject).progress(c.notify):c[f[0]+"With"](this===d?c.promise():this,g?[a]:arguments)})}),a=null}).promise()},promise:function(a){return null!=a?n.extend(a,d):d}},e={};return d.pipe=d.then,n.each(b,function(a,f){var g=f[2],h=f[3];d[f[1]]=g.add,h&&g.add(function(){c=h},b[1^a][2].disable,b[2][2].lock),e[f[0]]=function(){return e[f[0]+"With"](this===e?d:this,arguments),this},e[f[0]+"With"]=g.fireWith}),d.promise(e),a&&a.call(e,e),e},when:function(a){var b=0,c=d.call(arguments),e=c.length,f=1!==e||a&&n.isFunction(a.promise)?e:0,g=1===f?a:n.Deferred(),h=function(a,b,c){return function(e){b[a]=this,c[a]=arguments.length>1?d.call(arguments):e,c===i?g.notifyWith(b,c):--f||g.resolveWith(b,c)}},i,j,k;if(e>1)for(i=new Array(e),j=new Array(e),k=new Array(e);e>b;b++)c[b]&&n.isFunction(c[b].promise)?c[b].promise().done(h(b,k,c)).fail(g.reject).progress(h(b,j,i)):--f;return f||g.resolveWith(k,c),g.promise()}});var H;n.fn.ready=function(a){return n.ready.promise().done(a),this},n.extend({isReady:!1,readyWait:1,holdReady:function(a){a?n.readyWait++:n.ready(!0)},ready:function(a){(a===!0?--n.readyWait:n.isReady)||(n.isReady=!0,a!==!0&&--n.readyWait>0||(H.resolveWith(l,[n]),n.fn.triggerHandler&&(n(l).triggerHandler("ready"),n(l).off("ready"))))}});function I(){l.removeEventListener("DOMContentLoaded",I,!1),a.removeEventListener("load",I,!1),n.ready()}n.ready.promise=function(b){return H||(H=n.Deferred(),"complete"===l.readyState?setTimeout(n.ready):(l.addEventListener("DOMContentLoaded",I,!1),a.addEventListener("load",I,!1))),H.promise(b)},n.ready.promise();var J=n.access=function(a,b,c,d,e,f,g){var h=0,i=a.length,j=null==c;if("object"===n.type(c)){e=!0;for(h in c)n.access(a,b,h,c[h],!0,f,g)}else if(void 0!==d&&(e=!0,n.isFunction(d)||(g=!0),j&&(g?(b.call(a,d),b=null):(j=b,b=function(a,b,c){return j.call(n(a),c)})),b))for(;i>h;h++)b(a[h],c,g?d:d.call(a[h],h,b(a[h],c)));return e?a:j?b.call(a):i?b(a[0],c):f};n.acceptData=function(a){return 1===a.nodeType||9===a.nodeType||!+a.nodeType};function K(){Object.defineProperty(this.cache={},0,{get:function(){return{}}}),this.expando=n.expando+K.uid++}K.uid=1,K.accepts=n.acceptData,K.prototype={key:function(a){if(!K.accepts(a))return 0;var b={},c=a[this.expando];if(!c){c=K.uid++;try{b[this.expando]={value:c},Object.defineProperties(a,b)}catch(d){b[this.expando]=c,n.extend(a,b)}}return this.cache[c]||(this.cache[c]={}),c},set:function(a,b,c){var d,e=this.key(a),f=this.cache[e];if("string"==typeof b)f[b]=c;else if(n.isEmptyObject(f))n.extend(this.cache[e],b);else for(d in b)f[d]=b[d];return f},get:function(a,b){var c=this.cache[this.key(a)];return void 0===b?c:c[b]},access:function(a,b,c){var d;return void 0===b||b&&"string"==typeof b&&void 0===c?(d=this.get(a,b),void 0!==d?d:this.get(a,n.camelCase(b))):(this.set(a,b,c),void 0!==c?c:b)},remove:function(a,b){var c,d,e,f=this.key(a),g=this.cache[f];if(void 0===b)this.cache[f]={};else{n.isArray(b)?d=b.concat(b.map(n.camelCase)):(e=n.camelCase(b),b in g?d=[b,e]:(d=e,d=d in g?[d]:d.match(E)||[])),c=d.length;while(c--)delete g[d[c]]}},hasData:function(a){return!n.isEmptyObject(this.cache[a[this.expando]]||{})},discard:function(a){a[this.expando]&&delete this.cache[a[this.expando]]}};var L=new K,M=new K,N=/^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,O=/([A-Z])/g;function P(a,b,c){var d;if(void 0===c&&1===a.nodeType)if(d="data-"+b.replace(O,"-$1").toLowerCase(),c=a.getAttribute(d),"string"==typeof c){try{c="true"===c?!0:"false"===c?!1:"null"===c?null:+c+""===c?+c:N.test(c)?n.parseJSON(c):c}catch(e){}M.set(a,b,c)}else c=void 0;return c}n.extend({hasData:function(a){return M.hasData(a)||L.hasData(a)},data:function(a,b,c){
return M.access(a,b,c)},removeData:function(a,b){M.remove(a,b)},_data:function(a,b,c){return L.access(a,b,c)},_removeData:function(a,b){L.remove(a,b)}}),n.fn.extend({data:function(a,b){var c,d,e,f=this[0],g=f&&f.attributes;if(void 0===a){if(this.length&&(e=M.get(f),1===f.nodeType&&!L.get(f,"hasDataAttrs"))){c=g.length;while(c--)g[c]&&(d=g[c].name,0===d.indexOf("data-")&&(d=n.camelCase(d.slice(5)),P(f,d,e[d])));L.set(f,"hasDataAttrs",!0)}return e}return"object"==typeof a?this.each(function(){M.set(this,a)}):J(this,function(b){var c,d=n.camelCase(a);if(f&&void 0===b){if(c=M.get(f,a),void 0!==c)return c;if(c=M.get(f,d),void 0!==c)return c;if(c=P(f,d,void 0),void 0!==c)return c}else this.each(function(){var c=M.get(this,d);M.set(this,d,b),-1!==a.indexOf("-")&&void 0!==c&&M.set(this,a,b)})},null,b,arguments.length>1,null,!0)},removeData:function(a){return this.each(function(){M.remove(this,a)})}}),n.extend({queue:function(a,b,c){var d;return a?(b=(b||"fx")+"queue",d=L.get(a,b),c&&(!d||n.isArray(c)?d=L.access(a,b,n.makeArray(c)):d.push(c)),d||[]):void 0},dequeue:function(a,b){b=b||"fx";var c=n.queue(a,b),d=c.length,e=c.shift(),f=n._queueHooks(a,b),g=function(){n.dequeue(a,b)};"inprogress"===e&&(e=c.shift(),d--),e&&("fx"===b&&c.unshift("inprogress"),delete f.stop,e.call(a,g,f)),!d&&f&&f.empty.fire()},_queueHooks:function(a,b){var c=b+"queueHooks";return L.get(a,c)||L.access(a,c,{empty:n.Callbacks("once memory").add(function(){L.remove(a,[b+"queue",c])})})}}),n.fn.extend({queue:function(a,b){var c=2;return"string"!=typeof a&&(b=a,a="fx",c--),arguments.length<c?n.queue(this[0],a):void 0===b?this:this.each(function(){var c=n.queue(this,a,b);n._queueHooks(this,a),"fx"===a&&"inprogress"!==c[0]&&n.dequeue(this,a)})},dequeue:function(a){return this.each(function(){n.dequeue(this,a)})},clearQueue:function(a){return this.queue(a||"fx",[])},promise:function(a,b){var c,d=1,e=n.Deferred(),f=this,g=this.length,h=function(){--d||e.resolveWith(f,[f])};"string"!=typeof a&&(b=a,a=void 0),a=a||"fx";while(g--)c=L.get(f[g],a+"queueHooks"),c&&c.empty&&(d++,c.empty.add(h));return h(),e.promise(b)}});var Q=/[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,R=["Top","Right","Bottom","Left"],S=function(a,b){return a=b||a,"none"===n.css(a,"display")||!n.contains(a.ownerDocument,a)},T=/^(?:checkbox|radio)$/i;!function(){var a=l.createDocumentFragment(),b=a.appendChild(l.createElement("div")),c=l.createElement("input");c.setAttribute("type","radio"),c.setAttribute("checked","checked"),c.setAttribute("name","t"),b.appendChild(c),k.checkClone=b.cloneNode(!0).cloneNode(!0).lastChild.checked,b.innerHTML="<textarea>x</textarea>",k.noCloneChecked=!!b.cloneNode(!0).lastChild.defaultValue}();var U="undefined";k.focusinBubbles="onfocusin"in a;var V=/^key/,W=/^(?:mouse|pointer|contextmenu)|click/,X=/^(?:focusinfocus|focusoutblur)$/,Y=/^([^.]*)(?:\.(.+)|)$/;function Z(){return!0}function $(){return!1}function _(){try{return l.activeElement}catch(a){}}n.event={global:{},add:function(a,b,c,d,e){var f,g,h,i,j,k,l,m,o,p,q,r=L.get(a);if(r){c.handler&&(f=c,c=f.handler,e=f.selector),c.guid||(c.guid=n.guid++),(i=r.events)||(i=r.events={}),(g=r.handle)||(g=r.handle=function(b){return typeof n!==U&&n.event.triggered!==b.type?n.event.dispatch.apply(a,arguments):void 0}),b=(b||"").match(E)||[""],j=b.length;while(j--)h=Y.exec(b[j])||[],o=q=h[1],p=(h[2]||"").split(".").sort(),o&&(l=n.event.special[o]||{},o=(e?l.delegateType:l.bindType)||o,l=n.event.special[o]||{},k=n.extend({type:o,origType:q,data:d,handler:c,guid:c.guid,selector:e,needsContext:e&&n.expr.match.needsContext.test(e),namespace:p.join(".")},f),(m=i[o])||(m=i[o]=[],m.delegateCount=0,l.setup&&l.setup.call(a,d,p,g)!==!1||a.addEventListener&&a.addEventListener(o,g,!1)),l.add&&(l.add.call(a,k),k.handler.guid||(k.handler.guid=c.guid)),e?m.splice(m.delegateCount++,0,k):m.push(k),n.event.global[o]=!0)}},remove:function(a,b,c,d,e){var f,g,h,i,j,k,l,m,o,p,q,r=L.hasData(a)&&L.get(a);if(r&&(i=r.events)){b=(b||"").match(E)||[""],j=b.length;while(j--)if(h=Y.exec(b[j])||[],o=q=h[1],p=(h[2]||"").split(".").sort(),o){l=n.event.special[o]||{},o=(d?l.delegateType:l.bindType)||o,m=i[o]||[],h=h[2]&&new RegExp("(^|\\.)"+p.join("\\.(?:.*\\.|)")+"(\\.|$)"),g=f=m.length;while(f--)k=m[f],!e&&q!==k.origType||c&&c.guid!==k.guid||h&&!h.test(k.namespace)||d&&d!==k.selector&&("**"!==d||!k.selector)||(m.splice(f,1),k.selector&&m.delegateCount--,l.remove&&l.remove.call(a,k));g&&!m.length&&(l.teardown&&l.teardown.call(a,p,r.handle)!==!1||n.removeEvent(a,o,r.handle),delete i[o])}else for(o in i)n.event.remove(a,o+b[j],c,d,!0);n.isEmptyObject(i)&&(delete r.handle,L.remove(a,"events"))}},trigger:function(b,c,d,e){var f,g,h,i,k,m,o,p=[d||l],q=j.call(b,"type")?b.type:b,r=j.call(b,"namespace")?b.namespace.split("."):[];if(g=h=d=d||l,3!==d.nodeType&&8!==d.nodeType&&!X.test(q+n.event.triggered)&&(q.indexOf(".")>=0&&(r=q.split("."),q=r.shift(),r.sort()),k=q.indexOf(":")<0&&"on"+q,b=b[n.expando]?b:new n.Event(q,"object"==typeof b&&b),b.isTrigger=e?2:3,b.namespace=r.join("."),b.namespace_re=b.namespace?new RegExp("(^|\\.)"+r.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,b.result=void 0,b.target||(b.target=d),c=null==c?[b]:n.makeArray(c,[b]),o=n.event.special[q]||{},e||!o.trigger||o.trigger.apply(d,c)!==!1)){if(!e&&!o.noBubble&&!n.isWindow(d)){for(i=o.delegateType||q,X.test(i+q)||(g=g.parentNode);g;g=g.parentNode)p.push(g),h=g;h===(d.ownerDocument||l)&&p.push(h.defaultView||h.parentWindow||a)}f=0;while((g=p[f++])&&!b.isPropagationStopped())b.type=f>1?i:o.bindType||q,m=(L.get(g,"events")||{})[b.type]&&L.get(g,"handle"),m&&m.apply(g,c),m=k&&g[k],m&&m.apply&&n.acceptData(g)&&(b.result=m.apply(g,c),b.result===!1&&b.preventDefault());return b.type=q,e||b.isDefaultPrevented()||o._default&&o._default.apply(p.pop(),c)!==!1||!n.acceptData(d)||k&&n.isFunction(d[q])&&!n.isWindow(d)&&(h=d[k],h&&(d[k]=null),n.event.triggered=q,d[q](),n.event.triggered=void 0,h&&(d[k]=h)),b.result}},dispatch:function(a){a=n.event.fix(a);var b,c,e,f,g,h=[],i=d.call(arguments),j=(L.get(this,"events")||{})[a.type]||[],k=n.event.special[a.type]||{};if(i[0]=a,a.delegateTarget=this,!k.preDispatch||k.preDispatch.call(this,a)!==!1){h=n.event.handlers.call(this,a,j),b=0;while((f=h[b++])&&!a.isPropagationStopped()){a.currentTarget=f.elem,c=0;while((g=f.handlers[c++])&&!a.isImmediatePropagationStopped())(!a.namespace_re||a.namespace_re.test(g.namespace))&&(a.handleObj=g,a.data=g.data,e=((n.event.special[g.origType]||{}).handle||g.handler).apply(f.elem,i),void 0!==e&&(a.result=e)===!1&&(a.preventDefault(),a.stopPropagation()))}return k.postDispatch&&k.postDispatch.call(this,a),a.result}},handlers:function(a,b){var c,d,e,f,g=[],h=b.delegateCount,i=a.target;if(h&&i.nodeType&&(!a.button||"click"!==a.type))for(;i!==this;i=i.parentNode||this)if(i.disabled!==!0||"click"!==a.type){for(d=[],c=0;h>c;c++)f=b[c],e=f.selector+" ",void 0===d[e]&&(d[e]=f.needsContext?n(e,this).index(i)>=0:n.find(e,this,null,[i]).length),d[e]&&d.push(f);d.length&&g.push({elem:i,handlers:d})}return h<b.length&&g.push({elem:this,handlers:b.slice(h)}),g},props:"altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),fixHooks:{},keyHooks:{props:"char charCode key keyCode".split(" "),filter:function(a,b){return null==a.which&&(a.which=null!=b.charCode?b.charCode:b.keyCode),a}},mouseHooks:{props:"button buttons clientX clientY offsetX offsetY pageX pageY screenX screenY toElement".split(" "),filter:function(a,b){var c,d,e,f=b.button;return null==a.pageX&&null!=b.clientX&&(c=a.target.ownerDocument||l,d=c.documentElement,e=c.body,a.pageX=b.clientX+(d&&d.scrollLeft||e&&e.scrollLeft||0)-(d&&d.clientLeft||e&&e.clientLeft||0),a.pageY=b.clientY+(d&&d.scrollTop||e&&e.scrollTop||0)-(d&&d.clientTop||e&&e.clientTop||0)),a.which||void 0===f||(a.which=1&f?1:2&f?3:4&f?2:0),a}},fix:function(a){if(a[n.expando])return a;var b,c,d,e=a.type,f=a,g=this.fixHooks[e];g||(this.fixHooks[e]=g=W.test(e)?this.mouseHooks:V.test(e)?this.keyHooks:{}),d=g.props?this.props.concat(g.props):this.props,a=new n.Event(f),b=d.length;while(b--)c=d[b],a[c]=f[c];return a.target||(a.target=l),3===a.target.nodeType&&(a.target=a.target.parentNode),g.filter?g.filter(a,f):a},special:{load:{noBubble:!0},focus:{trigger:function(){return this!==_()&&this.focus?(this.focus(),!1):void 0},delegateType:"focusin"},blur:{trigger:function(){return this===_()&&this.blur?(this.blur(),!1):void 0},delegateType:"focusout"},click:{trigger:function(){return"checkbox"===this.type&&this.click&&n.nodeName(this,"input")?(this.click(),!1):void 0},_default:function(a){return n.nodeName(a.target,"a")}},beforeunload:{postDispatch:function(a){void 0!==a.result&&a.originalEvent&&(a.originalEvent.returnValue=a.result)}}},simulate:function(a,b,c,d){var e=n.extend(new n.Event,c,{type:a,isSimulated:!0,originalEvent:{}});d?n.event.trigger(e,null,b):n.event.dispatch.call(b,e),e.isDefaultPrevented()&&c.preventDefault()}},n.removeEvent=function(a,b,c){a.removeEventListener&&a.removeEventListener(b,c,!1)},n.Event=function(a,b){return this instanceof n.Event?(a&&a.type?(this.originalEvent=a,this.type=a.type,this.isDefaultPrevented=a.defaultPrevented||void 0===a.defaultPrevented&&a.returnValue===!1?Z:$):this.type=a,b&&n.extend(this,b),this.timeStamp=a&&a.timeStamp||n.now(),void(this[n.expando]=!0)):new n.Event(a,b)},n.Event.prototype={isDefaultPrevented:$,isPropagationStopped:$,isImmediatePropagationStopped:$,preventDefault:function(){var a=this.originalEvent;this.isDefaultPrevented=Z,a&&a.preventDefault&&a.preventDefault()},stopPropagation:function(){var a=this.originalEvent;this.isPropagationStopped=Z,a&&a.stopPropagation&&a.stopPropagation()},stopImmediatePropagation:function(){var a=this.originalEvent;this.isImmediatePropagationStopped=Z,a&&a.stopImmediatePropagation&&a.stopImmediatePropagation(),this.stopPropagation()}},n.each({mouseenter:"mouseover",mouseleave:"mouseout",pointerenter:"pointerover",pointerleave:"pointerout"},function(a,b){n.event.special[a]={delegateType:b,bindType:b,handle:function(a){var c,d=this,e=a.relatedTarget,f=a.handleObj;return(!e||e!==d&&!n.contains(d,e))&&(a.type=f.origType,c=f.handler.apply(this,arguments),a.type=b),c}}}),k.focusinBubbles||n.each({focus:"focusin",blur:"focusout"},function(a,b){var c=function(a){n.event.simulate(b,a.target,n.event.fix(a),!0)};n.event.special[b]={setup:function(){var d=this.ownerDocument||this,e=L.access(d,b);e||d.addEventListener(a,c,!0),L.access(d,b,(e||0)+1)},teardown:function(){var d=this.ownerDocument||this,e=L.access(d,b)-1;e?L.access(d,b,e):(d.removeEventListener(a,c,!0),L.remove(d,b))}}}),n.fn.extend({on:function(a,b,c,d,e){var f,g;if("object"==typeof a){"string"!=typeof b&&(c=c||b,b=void 0);for(g in a)this.on(g,b,c,a[g],e);return this}if(null==c&&null==d?(d=b,c=b=void 0):null==d&&("string"==typeof b?(d=c,c=void 0):(d=c,c=b,b=void 0)),d===!1)d=$;else if(!d)return this;return 1===e&&(f=d,d=function(a){return n().off(a),f.apply(this,arguments)},d.guid=f.guid||(f.guid=n.guid++)),this.each(function(){n.event.add(this,a,d,c,b)})},one:function(a,b,c,d){return this.on(a,b,c,d,1)},off:function(a,b,c){var d,e;if(a&&a.preventDefault&&a.handleObj)return d=a.handleObj,n(a.delegateTarget).off(d.namespace?d.origType+"."+d.namespace:d.origType,d.selector,d.handler),this;if("object"==typeof a){for(e in a)this.off(e,b,a[e]);return this}return(b===!1||"function"==typeof b)&&(c=b,b=void 0),c===!1&&(c=$),this.each(function(){n.event.remove(this,a,c,b)})},trigger:function(a,b){return this.each(function(){n.event.trigger(a,b,this)})},triggerHandler:function(a,b){var c=this[0];return c?n.event.trigger(a,b,c,!0):void 0}});var aa=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,ba=/<([\w:]+)/,ca=/<|&#?\w+;/,da=/<(?:script|style|link)/i,ea=/checked\s*(?:[^=]|=\s*.checked.)/i,fa=/^$|\/(?:java|ecma)script/i,ga=/^true\/(.*)/,ha=/^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,ia={option:[1,"<select multiple='multiple'>","</select>"],thead:[1,"<table>","</table>"],col:[2,"<table><colgroup>","</colgroup></table>"],tr:[2,"<table><tbody>","</tbody></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],_default:[0,"",""]};ia.optgroup=ia.option,ia.tbody=ia.tfoot=ia.colgroup=ia.caption=ia.thead,ia.th=ia.td;function ja(a,b){return n.nodeName(a,"table")&&n.nodeName(11!==b.nodeType?b:b.firstChild,"tr")?a.getElementsByTagName("tbody")[0]||a.appendChild(a.ownerDocument.createElement("tbody")):a}function ka(a){return a.type=(null!==a.getAttribute("type"))+"/"+a.type,a}function la(a){var b=ga.exec(a.type);return b?a.type=b[1]:a.removeAttribute("type"),a}function ma(a,b){for(var c=0,d=a.length;d>c;c++)L.set(a[c],"globalEval",!b||L.get(b[c],"globalEval"))}function na(a,b){var c,d,e,f,g,h,i,j;if(1===b.nodeType){if(L.hasData(a)&&(f=L.access(a),g=L.set(b,f),j=f.events)){delete g.handle,g.events={};for(e in j)for(c=0,d=j[e].length;d>c;c++)n.event.add(b,e,j[e][c])}M.hasData(a)&&(h=M.access(a),i=n.extend({},h),M.set(b,i))}}function oa(a,b){var c=a.getElementsByTagName?a.getElementsByTagName(b||"*"):a.querySelectorAll?a.querySelectorAll(b||"*"):[];return void 0===b||b&&n.nodeName(a,b)?n.merge([a],c):c}function pa(a,b){var c=b.nodeName.toLowerCase();"input"===c&&T.test(a.type)?b.checked=a.checked:("input"===c||"textarea"===c)&&(b.defaultValue=a.defaultValue)}n.extend({clone:function(a,b,c){var d,e,f,g,h=a.cloneNode(!0),i=n.contains(a.ownerDocument,a);if(!(k.noCloneChecked||1!==a.nodeType&&11!==a.nodeType||n.isXMLDoc(a)))for(g=oa(h),f=oa(a),d=0,e=f.length;e>d;d++)pa(f[d],g[d]);if(b)if(c)for(f=f||oa(a),g=g||oa(h),d=0,e=f.length;e>d;d++)na(f[d],g[d]);else na(a,h);return g=oa(h,"script"),g.length>0&&ma(g,!i&&oa(a,"script")),h},buildFragment:function(a,b,c,d){for(var e,f,g,h,i,j,k=b.createDocumentFragment(),l=[],m=0,o=a.length;o>m;m++)if(e=a[m],e||0===e)if("object"===n.type(e))n.merge(l,e.nodeType?[e]:e);else if(ca.test(e)){f=f||k.appendChild(b.createElement("div")),g=(ba.exec(e)||["",""])[1].toLowerCase(),h=ia[g]||ia._default,f.innerHTML=h[1]+e.replace(aa,"<$1></$2>")+h[2],j=h[0];while(j--)f=f.lastChild;n.merge(l,f.childNodes),f=k.firstChild,f.textContent=""}else l.push(b.createTextNode(e));k.textContent="",m=0;while(e=l[m++])if((!d||-1===n.inArray(e,d))&&(i=n.contains(e.ownerDocument,e),f=oa(k.appendChild(e),"script"),i&&ma(f),c)){j=0;while(e=f[j++])fa.test(e.type||"")&&c.push(e)}return k},cleanData:function(a){for(var b,c,d,e,f=n.event.special,g=0;void 0!==(c=a[g]);g++){if(n.acceptData(c)&&(e=c[L.expando],e&&(b=L.cache[e]))){if(b.events)for(d in b.events)f[d]?n.event.remove(c,d):n.removeEvent(c,d,b.handle);L.cache[e]&&delete L.cache[e]}delete M.cache[c[M.expando]]}}}),n.fn.extend({text:function(a){return J(this,function(a){return void 0===a?n.text(this):this.empty().each(function(){(1===this.nodeType||11===this.nodeType||9===this.nodeType)&&(this.textContent=a)})},null,a,arguments.length)},append:function(){return this.domManip(arguments,function(a){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var b=ja(this,a);b.appendChild(a)}})},prepend:function(){return this.domManip(arguments,function(a){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var b=ja(this,a);b.insertBefore(a,b.firstChild)}})},before:function(){return this.domManip(arguments,function(a){this.parentNode&&this.parentNode.insertBefore(a,this)})},after:function(){return this.domManip(arguments,function(a){this.parentNode&&this.parentNode.insertBefore(a,this.nextSibling)})},remove:function(a,b){for(var c,d=a?n.filter(a,this):this,e=0;null!=(c=d[e]);e++)b||1!==c.nodeType||n.cleanData(oa(c)),c.parentNode&&(b&&n.contains(c.ownerDocument,c)&&ma(oa(c,"script")),c.parentNode.removeChild(c));return this},empty:function(){for(var a,b=0;null!=(a=this[b]);b++)1===a.nodeType&&(n.cleanData(oa(a,!1)),a.textContent="");return this},clone:function(a,b){return a=null==a?!1:a,b=null==b?a:b,this.map(function(){return n.clone(this,a,b)})},html:function(a){return J(this,function(a){var b=this[0]||{},c=0,d=this.length;if(void 0===a&&1===b.nodeType)return b.innerHTML;if("string"==typeof a&&!da.test(a)&&!ia[(ba.exec(a)||["",""])[1].toLowerCase()]){a=a.replace(aa,"<$1></$2>");try{for(;d>c;c++)b=this[c]||{},1===b.nodeType&&(n.cleanData(oa(b,!1)),b.innerHTML=a);b=0}catch(e){}}b&&this.empty().append(a)},null,a,arguments.length)},replaceWith:function(){var a=arguments[0];return this.domManip(arguments,function(b){a=this.parentNode,n.cleanData(oa(this)),a&&a.replaceChild(b,this)}),a&&(a.length||a.nodeType)?this:this.remove()},detach:function(a){return this.remove(a,!0)},domManip:function(a,b){a=e.apply([],a);var c,d,f,g,h,i,j=0,l=this.length,m=this,o=l-1,p=a[0],q=n.isFunction(p);if(q||l>1&&"string"==typeof p&&!k.checkClone&&ea.test(p))return this.each(function(c){var d=m.eq(c);q&&(a[0]=p.call(this,c,d.html())),d.domManip(a,b)});if(l&&(c=n.buildFragment(a,this[0].ownerDocument,!1,this),d=c.firstChild,1===c.childNodes.length&&(c=d),d)){for(f=n.map(oa(c,"script"),ka),g=f.length;l>j;j++)h=c,j!==o&&(h=n.clone(h,!0,!0),g&&n.merge(f,oa(h,"script"))),b.call(this[j],h,j);if(g)for(i=f[f.length-1].ownerDocument,n.map(f,la),j=0;g>j;j++)h=f[j],fa.test(h.type||"")&&!L.access(h,"globalEval")&&n.contains(i,h)&&(h.src?n._evalUrl&&n._evalUrl(h.src):n.globalEval(h.textContent.replace(ha,"")))}return this}}),n.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(a,b){n.fn[a]=function(a){for(var c,d=[],e=n(a),g=e.length-1,h=0;g>=h;h++)c=h===g?this:this.clone(!0),n(e[h])[b](c),f.apply(d,c.get());return this.pushStack(d)}});var qa,ra={};function sa(b,c){var d,e=n(c.createElement(b)).appendTo(c.body),f=a.getDefaultComputedStyle&&(d=a.getDefaultComputedStyle(e[0]))?d.display:n.css(e[0],"display");return e.detach(),f}function ta(a){var b=l,c=ra[a];return c||(c=sa(a,b),"none"!==c&&c||(qa=(qa||n("<iframe frameborder='0' width='0' height='0'/>")).appendTo(b.documentElement),b=qa[0].contentDocument,b.write(),b.close(),c=sa(a,b),qa.detach()),ra[a]=c),c}var ua=/^margin/,va=new RegExp("^("+Q+")(?!px)[a-z%]+$","i"),wa=function(b){return b.ownerDocument.defaultView.opener?b.ownerDocument.defaultView.getComputedStyle(b,null):a.getComputedStyle(b,null)};function xa(a,b,c){var d,e,f,g,h=a.style;return c=c||wa(a),c&&(g=c.getPropertyValue(b)||c[b]),c&&(""!==g||n.contains(a.ownerDocument,a)||(g=n.style(a,b)),va.test(g)&&ua.test(b)&&(d=h.width,e=h.minWidth,f=h.maxWidth,h.minWidth=h.maxWidth=h.width=g,g=c.width,h.width=d,h.minWidth=e,h.maxWidth=f)),void 0!==g?g+"":g}function ya(a,b){return{get:function(){return a()?void delete this.get:(this.get=b).apply(this,arguments)}}}!function(){var b,c,d=l.documentElement,e=l.createElement("div"),f=l.createElement("div");if(f.style){f.style.backgroundClip="content-box",f.cloneNode(!0).style.backgroundClip="",k.clearCloneStyle="content-box"===f.style.backgroundClip,e.style.cssText="border:0;width:0;height:0;top:0;left:-9999px;margin-top:1px;position:absolute",e.appendChild(f);function g(){f.style.cssText="-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:block;margin-top:1%;top:1%;border:1px;padding:1px;width:4px;position:absolute",f.innerHTML="",d.appendChild(e);var g=a.getComputedStyle(f,null);b="1%"!==g.top,c="4px"===g.width,d.removeChild(e)}a.getComputedStyle&&n.extend(k,{pixelPosition:function(){return g(),b},boxSizingReliable:function(){return null==c&&g(),c},reliableMarginRight:function(){var b,c=f.appendChild(l.createElement("div"));return c.style.cssText=f.style.cssText="-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:0",c.style.marginRight=c.style.width="0",f.style.width="1px",d.appendChild(e),b=!parseFloat(a.getComputedStyle(c,null).marginRight),d.removeChild(e),f.removeChild(c),b}})}}(),n.swap=function(a,b,c,d){var e,f,g={};for(f in b)g[f]=a.style[f],a.style[f]=b[f];e=c.apply(a,d||[]);for(f in b)a.style[f]=g[f];return e};var za=/^(none|table(?!-c[ea]).+)/,Aa=new RegExp("^("+Q+")(.*)$","i"),Ba=new RegExp("^([+-])=("+Q+")","i"),Ca={position:"absolute",visibility:"hidden",display:"block"},Da={letterSpacing:"0",fontWeight:"400"},Ea=["Webkit","O","Moz","ms"];function Fa(a,b){if(b in a)return b;var c=b[0].toUpperCase()+b.slice(1),d=b,e=Ea.length;while(e--)if(b=Ea[e]+c,b in a)return b;return d}function Ga(a,b,c){var d=Aa.exec(b);return d?Math.max(0,d[1]-(c||0))+(d[2]||"px"):b}function Ha(a,b,c,d,e){for(var f=c===(d?"border":"content")?4:"width"===b?1:0,g=0;4>f;f+=2)"margin"===c&&(g+=n.css(a,c+R[f],!0,e)),d?("content"===c&&(g-=n.css(a,"padding"+R[f],!0,e)),"margin"!==c&&(g-=n.css(a,"border"+R[f]+"Width",!0,e))):(g+=n.css(a,"padding"+R[f],!0,e),"padding"!==c&&(g+=n.css(a,"border"+R[f]+"Width",!0,e)));return g}function Ia(a,b,c){var d=!0,e="width"===b?a.offsetWidth:a.offsetHeight,f=wa(a),g="border-box"===n.css(a,"boxSizing",!1,f);if(0>=e||null==e){if(e=xa(a,b,f),(0>e||null==e)&&(e=a.style[b]),va.test(e))return e;d=g&&(k.boxSizingReliable()||e===a.style[b]),e=parseFloat(e)||0}return e+Ha(a,b,c||(g?"border":"content"),d,f)+"px"}function Ja(a,b){for(var c,d,e,f=[],g=0,h=a.length;h>g;g++)d=a[g],d.style&&(f[g]=L.get(d,"olddisplay"),c=d.style.display,b?(f[g]||"none"!==c||(d.style.display=""),""===d.style.display&&S(d)&&(f[g]=L.access(d,"olddisplay",ta(d.nodeName)))):(e=S(d),"none"===c&&e||L.set(d,"olddisplay",e?c:n.css(d,"display"))));for(g=0;h>g;g++)d=a[g],d.style&&(b&&"none"!==d.style.display&&""!==d.style.display||(d.style.display=b?f[g]||"":"none"));return a}n.extend({cssHooks:{opacity:{get:function(a,b){if(b){var c=xa(a,"opacity");return""===c?"1":c}}}},cssNumber:{columnCount:!0,fillOpacity:!0,flexGrow:!0,flexShrink:!0,fontWeight:!0,lineHeight:!0,opacity:!0,order:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{"float":"cssFloat"},style:function(a,b,c,d){if(a&&3!==a.nodeType&&8!==a.nodeType&&a.style){var e,f,g,h=n.camelCase(b),i=a.style;return b=n.cssProps[h]||(n.cssProps[h]=Fa(i,h)),g=n.cssHooks[b]||n.cssHooks[h],void 0===c?g&&"get"in g&&void 0!==(e=g.get(a,!1,d))?e:i[b]:(f=typeof c,"string"===f&&(e=Ba.exec(c))&&(c=(e[1]+1)*e[2]+parseFloat(n.css(a,b)),f="number"),null!=c&&c===c&&("number"!==f||n.cssNumber[h]||(c+="px"),k.clearCloneStyle||""!==c||0!==b.indexOf("background")||(i[b]="inherit"),g&&"set"in g&&void 0===(c=g.set(a,c,d))||(i[b]=c)),void 0)}},css:function(a,b,c,d){var e,f,g,h=n.camelCase(b);return b=n.cssProps[h]||(n.cssProps[h]=Fa(a.style,h)),g=n.cssHooks[b]||n.cssHooks[h],g&&"get"in g&&(e=g.get(a,!0,c)),void 0===e&&(e=xa(a,b,d)),"normal"===e&&b in Da&&(e=Da[b]),""===c||c?(f=parseFloat(e),c===!0||n.isNumeric(f)?f||0:e):e}}),n.each(["height","width"],function(a,b){n.cssHooks[b]={get:function(a,c,d){return c?za.test(n.css(a,"display"))&&0===a.offsetWidth?n.swap(a,Ca,function(){return Ia(a,b,d)}):Ia(a,b,d):void 0},set:function(a,c,d){var e=d&&wa(a);return Ga(a,c,d?Ha(a,b,d,"border-box"===n.css(a,"boxSizing",!1,e),e):0)}}}),n.cssHooks.marginRight=ya(k.reliableMarginRight,function(a,b){return b?n.swap(a,{display:"inline-block"},xa,[a,"marginRight"]):void 0}),n.each({margin:"",padding:"",border:"Width"},function(a,b){n.cssHooks[a+b]={expand:function(c){for(var d=0,e={},f="string"==typeof c?c.split(" "):[c];4>d;d++)e[a+R[d]+b]=f[d]||f[d-2]||f[0];return e}},ua.test(a)||(n.cssHooks[a+b].set=Ga)}),n.fn.extend({css:function(a,b){return J(this,function(a,b,c){var d,e,f={},g=0;if(n.isArray(b)){for(d=wa(a),e=b.length;e>g;g++)f[b[g]]=n.css(a,b[g],!1,d);return f}return void 0!==c?n.style(a,b,c):n.css(a,b)},a,b,arguments.length>1)},show:function(){return Ja(this,!0)},hide:function(){return Ja(this)},toggle:function(a){return"boolean"==typeof a?a?this.show():this.hide():this.each(function(){S(this)?n(this).show():n(this).hide()})}});function Ka(a,b,c,d,e){return new Ka.prototype.init(a,b,c,d,e)}n.Tween=Ka,Ka.prototype={constructor:Ka,init:function(a,b,c,d,e,f){this.elem=a,this.prop=c,this.easing=e||"swing",this.options=b,this.start=this.now=this.cur(),this.end=d,this.unit=f||(n.cssNumber[c]?"":"px")},cur:function(){var a=Ka.propHooks[this.prop];return a&&a.get?a.get(this):Ka.propHooks._default.get(this)},run:function(a){var b,c=Ka.propHooks[this.prop];return this.options.duration?this.pos=b=n.easing[this.easing](a,this.options.duration*a,0,1,this.options.duration):this.pos=b=a,this.now=(this.end-this.start)*b+this.start,this.options.step&&this.options.step.call(this.elem,this.now,this),c&&c.set?c.set(this):Ka.propHooks._default.set(this),this}},Ka.prototype.init.prototype=Ka.prototype,Ka.propHooks={_default:{get:function(a){var b;return null==a.elem[a.prop]||a.elem.style&&null!=a.elem.style[a.prop]?(b=n.css(a.elem,a.prop,""),b&&"auto"!==b?b:0):a.elem[a.prop]},set:function(a){n.fx.step[a.prop]?n.fx.step[a.prop](a):a.elem.style&&(null!=a.elem.style[n.cssProps[a.prop]]||n.cssHooks[a.prop])?n.style(a.elem,a.prop,a.now+a.unit):a.elem[a.prop]=a.now}}},Ka.propHooks.scrollTop=Ka.propHooks.scrollLeft={set:function(a){a.elem.nodeType&&a.elem.parentNode&&(a.elem[a.prop]=a.now)}},n.easing={linear:function(a){return a},swing:function(a){return.5-Math.cos(a*Math.PI)/2}},n.fx=Ka.prototype.init,n.fx.step={};var La,Ma,Na=/^(?:toggle|show|hide)$/,Oa=new RegExp("^(?:([+-])=|)("+Q+")([a-z%]*)$","i"),Pa=/queueHooks$/,Qa=[Va],Ra={"*":[function(a,b){var c=this.createTween(a,b),d=c.cur(),e=Oa.exec(b),f=e&&e[3]||(n.cssNumber[a]?"":"px"),g=(n.cssNumber[a]||"px"!==f&&+d)&&Oa.exec(n.css(c.elem,a)),h=1,i=20;if(g&&g[3]!==f){f=f||g[3],e=e||[],g=+d||1;do h=h||".5",g/=h,n.style(c.elem,a,g+f);while(h!==(h=c.cur()/d)&&1!==h&&--i)}return e&&(g=c.start=+g||+d||0,c.unit=f,c.end=e[1]?g+(e[1]+1)*e[2]:+e[2]),c}]};function Sa(){return setTimeout(function(){La=void 0}),La=n.now()}function Ta(a,b){var c,d=0,e={height:a};for(b=b?1:0;4>d;d+=2-b)c=R[d],e["margin"+c]=e["padding"+c]=a;return b&&(e.opacity=e.width=a),e}function Ua(a,b,c){for(var d,e=(Ra[b]||[]).concat(Ra["*"]),f=0,g=e.length;g>f;f++)if(d=e[f].call(c,b,a))return d}function Va(a,b,c){var d,e,f,g,h,i,j,k,l=this,m={},o=a.style,p=a.nodeType&&S(a),q=L.get(a,"fxshow");c.queue||(h=n._queueHooks(a,"fx"),null==h.unqueued&&(h.unqueued=0,i=h.empty.fire,h.empty.fire=function(){h.unqueued||i()}),h.unqueued++,l.always(function(){l.always(function(){h.unqueued--,n.queue(a,"fx").length||h.empty.fire()})})),1===a.nodeType&&("height"in b||"width"in b)&&(c.overflow=[o.overflow,o.overflowX,o.overflowY],j=n.css(a,"display"),k="none"===j?L.get(a,"olddisplay")||ta(a.nodeName):j,"inline"===k&&"none"===n.css(a,"float")&&(o.display="inline-block")),c.overflow&&(o.overflow="hidden",l.always(function(){o.overflow=c.overflow[0],o.overflowX=c.overflow[1],o.overflowY=c.overflow[2]}));for(d in b)if(e=b[d],Na.exec(e)){if(delete b[d],f=f||"toggle"===e,e===(p?"hide":"show")){if("show"!==e||!q||void 0===q[d])continue;p=!0}m[d]=q&&q[d]||n.style(a,d)}else j=void 0;if(n.isEmptyObject(m))"inline"===("none"===j?ta(a.nodeName):j)&&(o.display=j);else{q?"hidden"in q&&(p=q.hidden):q=L.access(a,"fxshow",{}),f&&(q.hidden=!p),p?n(a).show():l.done(function(){n(a).hide()}),l.done(function(){var b;L.remove(a,"fxshow");for(b in m)n.style(a,b,m[b])});for(d in m)g=Ua(p?q[d]:0,d,l),d in q||(q[d]=g.start,p&&(g.end=g.start,g.start="width"===d||"height"===d?1:0))}}function Wa(a,b){var c,d,e,f,g;for(c in a)if(d=n.camelCase(c),e=b[d],f=a[c],n.isArray(f)&&(e=f[1],f=a[c]=f[0]),c!==d&&(a[d]=f,delete a[c]),g=n.cssHooks[d],g&&"expand"in g){f=g.expand(f),delete a[d];for(c in f)c in a||(a[c]=f[c],b[c]=e)}else b[d]=e}function Xa(a,b,c){var d,e,f=0,g=Qa.length,h=n.Deferred().always(function(){delete i.elem}),i=function(){if(e)return!1;for(var b=La||Sa(),c=Math.max(0,j.startTime+j.duration-b),d=c/j.duration||0,f=1-d,g=0,i=j.tweens.length;i>g;g++)j.tweens[g].run(f);return h.notifyWith(a,[j,f,c]),1>f&&i?c:(h.resolveWith(a,[j]),!1)},j=h.promise({elem:a,props:n.extend({},b),opts:n.extend(!0,{specialEasing:{}},c),originalProperties:b,originalOptions:c,startTime:La||Sa(),duration:c.duration,tweens:[],createTween:function(b,c){var d=n.Tween(a,j.opts,b,c,j.opts.specialEasing[b]||j.opts.easing);return j.tweens.push(d),d},stop:function(b){var c=0,d=b?j.tweens.length:0;if(e)return this;for(e=!0;d>c;c++)j.tweens[c].run(1);return b?h.resolveWith(a,[j,b]):h.rejectWith(a,[j,b]),this}}),k=j.props;for(Wa(k,j.opts.specialEasing);g>f;f++)if(d=Qa[f].call(j,a,k,j.opts))return d;return n.map(k,Ua,j),n.isFunction(j.opts.start)&&j.opts.start.call(a,j),n.fx.timer(n.extend(i,{elem:a,anim:j,queue:j.opts.queue})),j.progress(j.opts.progress).done(j.opts.done,j.opts.complete).fail(j.opts.fail).always(j.opts.always)}n.Animation=n.extend(Xa,{tweener:function(a,b){n.isFunction(a)?(b=a,a=["*"]):a=a.split(" ");for(var c,d=0,e=a.length;e>d;d++)c=a[d],Ra[c]=Ra[c]||[],Ra[c].unshift(b)},prefilter:function(a,b){b?Qa.unshift(a):Qa.push(a)}}),n.speed=function(a,b,c){var d=a&&"object"==typeof a?n.extend({},a):{complete:c||!c&&b||n.isFunction(a)&&a,duration:a,easing:c&&b||b&&!n.isFunction(b)&&b};return d.duration=n.fx.off?0:"number"==typeof d.duration?d.duration:d.duration in n.fx.speeds?n.fx.speeds[d.duration]:n.fx.speeds._default,(null==d.queue||d.queue===!0)&&(d.queue="fx"),d.old=d.complete,d.complete=function(){n.isFunction(d.old)&&d.old.call(this),d.queue&&n.dequeue(this,d.queue)},d},n.fn.extend({fadeTo:function(a,b,c,d){return this.filter(S).css("opacity",0).show().end().animate({opacity:b},a,c,d)},animate:function(a,b,c,d){var e=n.isEmptyObject(a),f=n.speed(b,c,d),g=function(){var b=Xa(this,n.extend({},a),f);(e||L.get(this,"finish"))&&b.stop(!0)};return g.finish=g,e||f.queue===!1?this.each(g):this.queue(f.queue,g)},stop:function(a,b,c){var d=function(a){var b=a.stop;delete a.stop,b(c)};return"string"!=typeof a&&(c=b,b=a,a=void 0),b&&a!==!1&&this.queue(a||"fx",[]),this.each(function(){var b=!0,e=null!=a&&a+"queueHooks",f=n.timers,g=L.get(this);if(e)g[e]&&g[e].stop&&d(g[e]);else for(e in g)g[e]&&g[e].stop&&Pa.test(e)&&d(g[e]);for(e=f.length;e--;)f[e].elem!==this||null!=a&&f[e].queue!==a||(f[e].anim.stop(c),b=!1,f.splice(e,1));(b||!c)&&n.dequeue(this,a)})},finish:function(a){return a!==!1&&(a=a||"fx"),this.each(function(){var b,c=L.get(this),d=c[a+"queue"],e=c[a+"queueHooks"],f=n.timers,g=d?d.length:0;for(c.finish=!0,n.queue(this,a,[]),e&&e.stop&&e.stop.call(this,!0),b=f.length;b--;)f[b].elem===this&&f[b].queue===a&&(f[b].anim.stop(!0),f.splice(b,1));for(b=0;g>b;b++)d[b]&&d[b].finish&&d[b].finish.call(this);delete c.finish})}}),n.each(["toggle","show","hide"],function(a,b){var c=n.fn[b];n.fn[b]=function(a,d,e){return null==a||"boolean"==typeof a?c.apply(this,arguments):this.animate(Ta(b,!0),a,d,e)}}),n.each({slideDown:Ta("show"),slideUp:Ta("hide"),slideToggle:Ta("toggle"),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(a,b){n.fn[a]=function(a,c,d){return this.animate(b,a,c,d)}}),n.timers=[],n.fx.tick=function(){var a,b=0,c=n.timers;for(La=n.now();b<c.length;b++)a=c[b],a()||c[b]!==a||c.splice(b--,1);c.length||n.fx.stop(),La=void 0},n.fx.timer=function(a){n.timers.push(a),a()?n.fx.start():n.timers.pop()},n.fx.interval=13,n.fx.start=function(){Ma||(Ma=setInterval(n.fx.tick,n.fx.interval))},n.fx.stop=function(){clearInterval(Ma),Ma=null},n.fx.speeds={slow:600,fast:200,_default:400},n.fn.delay=function(a,b){return a=n.fx?n.fx.speeds[a]||a:a,b=b||"fx",this.queue(b,function(b,c){var d=setTimeout(b,a);c.stop=function(){clearTimeout(d)}})},function(){var a=l.createElement("input"),b=l.createElement("select"),c=b.appendChild(l.createElement("option"));a.type="checkbox",k.checkOn=""!==a.value,k.optSelected=c.selected,b.disabled=!0,k.optDisabled=!c.disabled,a=l.createElement("input"),a.value="t",a.type="radio",k.radioValue="t"===a.value}();var Ya,Za,$a=n.expr.attrHandle;n.fn.extend({attr:function(a,b){return J(this,n.attr,a,b,arguments.length>1)},removeAttr:function(a){return this.each(function(){n.removeAttr(this,a)})}}),n.extend({attr:function(a,b,c){var d,e,f=a.nodeType;if(a&&3!==f&&8!==f&&2!==f)return typeof a.getAttribute===U?n.prop(a,b,c):(1===f&&n.isXMLDoc(a)||(b=b.toLowerCase(),d=n.attrHooks[b]||(n.expr.match.bool.test(b)?Za:Ya)),
void 0===c?d&&"get"in d&&null!==(e=d.get(a,b))?e:(e=n.find.attr(a,b),null==e?void 0:e):null!==c?d&&"set"in d&&void 0!==(e=d.set(a,c,b))?e:(a.setAttribute(b,c+""),c):void n.removeAttr(a,b))},removeAttr:function(a,b){var c,d,e=0,f=b&&b.match(E);if(f&&1===a.nodeType)while(c=f[e++])d=n.propFix[c]||c,n.expr.match.bool.test(c)&&(a[d]=!1),a.removeAttribute(c)},attrHooks:{type:{set:function(a,b){if(!k.radioValue&&"radio"===b&&n.nodeName(a,"input")){var c=a.value;return a.setAttribute("type",b),c&&(a.value=c),b}}}}}),Za={set:function(a,b,c){return b===!1?n.removeAttr(a,c):a.setAttribute(c,c),c}},n.each(n.expr.match.bool.source.match(/\w+/g),function(a,b){var c=$a[b]||n.find.attr;$a[b]=function(a,b,d){var e,f;return d||(f=$a[b],$a[b]=e,e=null!=c(a,b,d)?b.toLowerCase():null,$a[b]=f),e}});var _a=/^(?:input|select|textarea|button)$/i;n.fn.extend({prop:function(a,b){return J(this,n.prop,a,b,arguments.length>1)},removeProp:function(a){return this.each(function(){delete this[n.propFix[a]||a]})}}),n.extend({propFix:{"for":"htmlFor","class":"className"},prop:function(a,b,c){var d,e,f,g=a.nodeType;if(a&&3!==g&&8!==g&&2!==g)return f=1!==g||!n.isXMLDoc(a),f&&(b=n.propFix[b]||b,e=n.propHooks[b]),void 0!==c?e&&"set"in e&&void 0!==(d=e.set(a,c,b))?d:a[b]=c:e&&"get"in e&&null!==(d=e.get(a,b))?d:a[b]},propHooks:{tabIndex:{get:function(a){return a.hasAttribute("tabindex")||_a.test(a.nodeName)||a.href?a.tabIndex:-1}}}}),k.optSelected||(n.propHooks.selected={get:function(a){var b=a.parentNode;return b&&b.parentNode&&b.parentNode.selectedIndex,null}}),n.each(["tabIndex","readOnly","maxLength","cellSpacing","cellPadding","rowSpan","colSpan","useMap","frameBorder","contentEditable"],function(){n.propFix[this.toLowerCase()]=this});var ab=/[\t\r\n\f]/g;n.fn.extend({addClass:function(a){var b,c,d,e,f,g,h="string"==typeof a&&a,i=0,j=this.length;if(n.isFunction(a))return this.each(function(b){n(this).addClass(a.call(this,b,this.className))});if(h)for(b=(a||"").match(E)||[];j>i;i++)if(c=this[i],d=1===c.nodeType&&(c.className?(" "+c.className+" ").replace(ab," "):" ")){f=0;while(e=b[f++])d.indexOf(" "+e+" ")<0&&(d+=e+" ");g=n.trim(d),c.className!==g&&(c.className=g)}return this},removeClass:function(a){var b,c,d,e,f,g,h=0===arguments.length||"string"==typeof a&&a,i=0,j=this.length;if(n.isFunction(a))return this.each(function(b){n(this).removeClass(a.call(this,b,this.className))});if(h)for(b=(a||"").match(E)||[];j>i;i++)if(c=this[i],d=1===c.nodeType&&(c.className?(" "+c.className+" ").replace(ab," "):"")){f=0;while(e=b[f++])while(d.indexOf(" "+e+" ")>=0)d=d.replace(" "+e+" "," ");g=a?n.trim(d):"",c.className!==g&&(c.className=g)}return this},toggleClass:function(a,b){var c=typeof a;return"boolean"==typeof b&&"string"===c?b?this.addClass(a):this.removeClass(a):this.each(n.isFunction(a)?function(c){n(this).toggleClass(a.call(this,c,this.className,b),b)}:function(){if("string"===c){var b,d=0,e=n(this),f=a.match(E)||[];while(b=f[d++])e.hasClass(b)?e.removeClass(b):e.addClass(b)}else(c===U||"boolean"===c)&&(this.className&&L.set(this,"__className__",this.className),this.className=this.className||a===!1?"":L.get(this,"__className__")||"")})},hasClass:function(a){for(var b=" "+a+" ",c=0,d=this.length;d>c;c++)if(1===this[c].nodeType&&(" "+this[c].className+" ").replace(ab," ").indexOf(b)>=0)return!0;return!1}});var bb=/\r/g;n.fn.extend({val:function(a){var b,c,d,e=this[0];{if(arguments.length)return d=n.isFunction(a),this.each(function(c){var e;1===this.nodeType&&(e=d?a.call(this,c,n(this).val()):a,null==e?e="":"number"==typeof e?e+="":n.isArray(e)&&(e=n.map(e,function(a){return null==a?"":a+""})),b=n.valHooks[this.type]||n.valHooks[this.nodeName.toLowerCase()],b&&"set"in b&&void 0!==b.set(this,e,"value")||(this.value=e))});if(e)return b=n.valHooks[e.type]||n.valHooks[e.nodeName.toLowerCase()],b&&"get"in b&&void 0!==(c=b.get(e,"value"))?c:(c=e.value,"string"==typeof c?c.replace(bb,""):null==c?"":c)}}}),n.extend({valHooks:{option:{get:function(a){var b=n.find.attr(a,"value");return null!=b?b:n.trim(n.text(a))}},select:{get:function(a){for(var b,c,d=a.options,e=a.selectedIndex,f="select-one"===a.type||0>e,g=f?null:[],h=f?e+1:d.length,i=0>e?h:f?e:0;h>i;i++)if(c=d[i],!(!c.selected&&i!==e||(k.optDisabled?c.disabled:null!==c.getAttribute("disabled"))||c.parentNode.disabled&&n.nodeName(c.parentNode,"optgroup"))){if(b=n(c).val(),f)return b;g.push(b)}return g},set:function(a,b){var c,d,e=a.options,f=n.makeArray(b),g=e.length;while(g--)d=e[g],(d.selected=n.inArray(d.value,f)>=0)&&(c=!0);return c||(a.selectedIndex=-1),f}}}}),n.each(["radio","checkbox"],function(){n.valHooks[this]={set:function(a,b){return n.isArray(b)?a.checked=n.inArray(n(a).val(),b)>=0:void 0}},k.checkOn||(n.valHooks[this].get=function(a){return null===a.getAttribute("value")?"on":a.value})}),n.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),function(a,b){n.fn[b]=function(a,c){return arguments.length>0?this.on(b,null,a,c):this.trigger(b)}}),n.fn.extend({hover:function(a,b){return this.mouseenter(a).mouseleave(b||a)},bind:function(a,b,c){return this.on(a,null,b,c)},unbind:function(a,b){return this.off(a,null,b)},delegate:function(a,b,c,d){return this.on(b,a,c,d)},undelegate:function(a,b,c){return 1===arguments.length?this.off(a,"**"):this.off(b,a||"**",c)}});var cb=n.now(),db=/\?/;n.parseJSON=function(a){return JSON.parse(a+"")},n.parseXML=function(a){var b,c;if(!a||"string"!=typeof a)return null;try{c=new DOMParser,b=c.parseFromString(a,"text/xml")}catch(d){b=void 0}return(!b||b.getElementsByTagName("parsererror").length)&&n.error("Invalid XML: "+a),b};var eb=/#.*$/,fb=/([?&])_=[^&]*/,gb=/^(.*?):[ \t]*([^\r\n]*)$/gm,hb=/^(?:about|app|app-storage|.+-extension|file|res|widget):$/,ib=/^(?:GET|HEAD)$/,jb=/^\/\//,kb=/^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/,lb={},mb={},nb="*/".concat("*"),ob=a.location.href,pb=kb.exec(ob.toLowerCase())||[];function qb(a){return function(b,c){"string"!=typeof b&&(c=b,b="*");var d,e=0,f=b.toLowerCase().match(E)||[];if(n.isFunction(c))while(d=f[e++])"+"===d[0]?(d=d.slice(1)||"*",(a[d]=a[d]||[]).unshift(c)):(a[d]=a[d]||[]).push(c)}}function rb(a,b,c,d){var e={},f=a===mb;function g(h){var i;return e[h]=!0,n.each(a[h]||[],function(a,h){var j=h(b,c,d);return"string"!=typeof j||f||e[j]?f?!(i=j):void 0:(b.dataTypes.unshift(j),g(j),!1)}),i}return g(b.dataTypes[0])||!e["*"]&&g("*")}function sb(a,b){var c,d,e=n.ajaxSettings.flatOptions||{};for(c in b)void 0!==b[c]&&((e[c]?a:d||(d={}))[c]=b[c]);return d&&n.extend(!0,a,d),a}function tb(a,b,c){var d,e,f,g,h=a.contents,i=a.dataTypes;while("*"===i[0])i.shift(),void 0===d&&(d=a.mimeType||b.getResponseHeader("Content-Type"));if(d)for(e in h)if(h[e]&&h[e].test(d)){i.unshift(e);break}if(i[0]in c)f=i[0];else{for(e in c){if(!i[0]||a.converters[e+" "+i[0]]){f=e;break}g||(g=e)}f=f||g}return f?(f!==i[0]&&i.unshift(f),c[f]):void 0}function ub(a,b,c,d){var e,f,g,h,i,j={},k=a.dataTypes.slice();if(k[1])for(g in a.converters)j[g.toLowerCase()]=a.converters[g];f=k.shift();while(f)if(a.responseFields[f]&&(c[a.responseFields[f]]=b),!i&&d&&a.dataFilter&&(b=a.dataFilter(b,a.dataType)),i=f,f=k.shift())if("*"===f)f=i;else if("*"!==i&&i!==f){if(g=j[i+" "+f]||j["* "+f],!g)for(e in j)if(h=e.split(" "),h[1]===f&&(g=j[i+" "+h[0]]||j["* "+h[0]])){g===!0?g=j[e]:j[e]!==!0&&(f=h[0],k.unshift(h[1]));break}if(g!==!0)if(g&&a["throws"])b=g(b);else try{b=g(b)}catch(l){return{state:"parsererror",error:g?l:"No conversion from "+i+" to "+f}}}return{state:"success",data:b}}n.extend({active:0,lastModified:{},etag:{},ajaxSettings:{url:ob,type:"GET",isLocal:hb.test(pb[1]),global:!0,processData:!0,async:!0,contentType:"application/x-www-form-urlencoded; charset=UTF-8",accepts:{"*":nb,text:"text/plain",html:"text/html",xml:"application/xml, text/xml",json:"application/json, text/javascript"},contents:{xml:/xml/,html:/html/,json:/json/},responseFields:{xml:"responseXML",text:"responseText",json:"responseJSON"},converters:{"* text":String,"text html":!0,"text json":n.parseJSON,"text xml":n.parseXML},flatOptions:{url:!0,context:!0}},ajaxSetup:function(a,b){return b?sb(sb(a,n.ajaxSettings),b):sb(n.ajaxSettings,a)},ajaxPrefilter:qb(lb),ajaxTransport:qb(mb),ajax:function(a,b){"object"==typeof a&&(b=a,a=void 0),b=b||{};var c,d,e,f,g,h,i,j,k=n.ajaxSetup({},b),l=k.context||k,m=k.context&&(l.nodeType||l.jquery)?n(l):n.event,o=n.Deferred(),p=n.Callbacks("once memory"),q=k.statusCode||{},r={},s={},t=0,u="canceled",v={readyState:0,getResponseHeader:function(a){var b;if(2===t){if(!f){f={};while(b=gb.exec(e))f[b[1].toLowerCase()]=b[2]}b=f[a.toLowerCase()]}return null==b?null:b},getAllResponseHeaders:function(){return 2===t?e:null},setRequestHeader:function(a,b){var c=a.toLowerCase();return t||(a=s[c]=s[c]||a,r[a]=b),this},overrideMimeType:function(a){return t||(k.mimeType=a),this},statusCode:function(a){var b;if(a)if(2>t)for(b in a)q[b]=[q[b],a[b]];else v.always(a[v.status]);return this},abort:function(a){var b=a||u;return c&&c.abort(b),x(0,b),this}};if(o.promise(v).complete=p.add,v.success=v.done,v.error=v.fail,k.url=((a||k.url||ob)+"").replace(eb,"").replace(jb,pb[1]+"//"),k.type=b.method||b.type||k.method||k.type,k.dataTypes=n.trim(k.dataType||"*").toLowerCase().match(E)||[""],null==k.crossDomain&&(h=kb.exec(k.url.toLowerCase()),k.crossDomain=!(!h||h[1]===pb[1]&&h[2]===pb[2]&&(h[3]||("http:"===h[1]?"80":"443"))===(pb[3]||("http:"===pb[1]?"80":"443")))),k.data&&k.processData&&"string"!=typeof k.data&&(k.data=n.param(k.data,k.traditional)),rb(lb,k,b,v),2===t)return v;i=n.event&&k.global,i&&0===n.active++&&n.event.trigger("ajaxStart"),k.type=k.type.toUpperCase(),k.hasContent=!ib.test(k.type),d=k.url,k.hasContent||(k.data&&(d=k.url+=(db.test(d)?"&":"?")+k.data,delete k.data),k.cache===!1&&(k.url=fb.test(d)?d.replace(fb,"$1_="+cb++):d+(db.test(d)?"&":"?")+"_="+cb++)),k.ifModified&&(n.lastModified[d]&&v.setRequestHeader("If-Modified-Since",n.lastModified[d]),n.etag[d]&&v.setRequestHeader("If-None-Match",n.etag[d])),(k.data&&k.hasContent&&k.contentType!==!1||b.contentType)&&v.setRequestHeader("Content-Type",k.contentType),v.setRequestHeader("Accept",k.dataTypes[0]&&k.accepts[k.dataTypes[0]]?k.accepts[k.dataTypes[0]]+("*"!==k.dataTypes[0]?", "+nb+"; q=0.01":""):k.accepts["*"]);for(j in k.headers)v.setRequestHeader(j,k.headers[j]);if(k.beforeSend&&(k.beforeSend.call(l,v,k)===!1||2===t))return v.abort();u="abort";for(j in{success:1,error:1,complete:1})v[j](k[j]);if(c=rb(mb,k,b,v)){v.readyState=1,i&&m.trigger("ajaxSend",[v,k]),k.async&&k.timeout>0&&(g=setTimeout(function(){v.abort("timeout")},k.timeout));try{t=1,c.send(r,x)}catch(w){if(!(2>t))throw w;x(-1,w)}}else x(-1,"No Transport");function x(a,b,f,h){var j,r,s,u,w,x=b;2!==t&&(t=2,g&&clearTimeout(g),c=void 0,e=h||"",v.readyState=a>0?4:0,j=a>=200&&300>a||304===a,f&&(u=tb(k,v,f)),u=ub(k,u,v,j),j?(k.ifModified&&(w=v.getResponseHeader("Last-Modified"),w&&(n.lastModified[d]=w),w=v.getResponseHeader("etag"),w&&(n.etag[d]=w)),204===a||"HEAD"===k.type?x="nocontent":304===a?x="notmodified":(x=u.state,r=u.data,s=u.error,j=!s)):(s=x,(a||!x)&&(x="error",0>a&&(a=0))),v.status=a,v.statusText=(b||x)+"",j?o.resolveWith(l,[r,x,v]):o.rejectWith(l,[v,x,s]),v.statusCode(q),q=void 0,i&&m.trigger(j?"ajaxSuccess":"ajaxError",[v,k,j?r:s]),p.fireWith(l,[v,x]),i&&(m.trigger("ajaxComplete",[v,k]),--n.active||n.event.trigger("ajaxStop")))}return v},getJSON:function(a,b,c){return n.get(a,b,c,"json")},getScript:function(a,b){return n.get(a,void 0,b,"script")}}),n.each(["get","post"],function(a,b){n[b]=function(a,c,d,e){return n.isFunction(c)&&(e=e||d,d=c,c=void 0),n.ajax({url:a,type:b,dataType:e,data:c,success:d})}}),n._evalUrl=function(a){return n.ajax({url:a,type:"GET",dataType:"script",async:!1,global:!1,"throws":!0})},n.fn.extend({wrapAll:function(a){var b;return n.isFunction(a)?this.each(function(b){n(this).wrapAll(a.call(this,b))}):(this[0]&&(b=n(a,this[0].ownerDocument).eq(0).clone(!0),this[0].parentNode&&b.insertBefore(this[0]),b.map(function(){var a=this;while(a.firstElementChild)a=a.firstElementChild;return a}).append(this)),this)},wrapInner:function(a){return this.each(n.isFunction(a)?function(b){n(this).wrapInner(a.call(this,b))}:function(){var b=n(this),c=b.contents();c.length?c.wrapAll(a):b.append(a)})},wrap:function(a){var b=n.isFunction(a);return this.each(function(c){n(this).wrapAll(b?a.call(this,c):a)})},unwrap:function(){return this.parent().each(function(){n.nodeName(this,"body")||n(this).replaceWith(this.childNodes)}).end()}}),n.expr.filters.hidden=function(a){return a.offsetWidth<=0&&a.offsetHeight<=0},n.expr.filters.visible=function(a){return!n.expr.filters.hidden(a)};var vb=/%20/g,wb=/\[\]$/,xb=/\r?\n/g,yb=/^(?:submit|button|image|reset|file)$/i,zb=/^(?:input|select|textarea|keygen)/i;function Ab(a,b,c,d){var e;if(n.isArray(b))n.each(b,function(b,e){c||wb.test(a)?d(a,e):Ab(a+"["+("object"==typeof e?b:"")+"]",e,c,d)});else if(c||"object"!==n.type(b))d(a,b);else for(e in b)Ab(a+"["+e+"]",b[e],c,d)}n.param=function(a,b){var c,d=[],e=function(a,b){b=n.isFunction(b)?b():null==b?"":b,d[d.length]=encodeURIComponent(a)+"="+encodeURIComponent(b)};if(void 0===b&&(b=n.ajaxSettings&&n.ajaxSettings.traditional),n.isArray(a)||a.jquery&&!n.isPlainObject(a))n.each(a,function(){e(this.name,this.value)});else for(c in a)Ab(c,a[c],b,e);return d.join("&").replace(vb,"+")},n.fn.extend({serialize:function(){return n.param(this.serializeArray())},serializeArray:function(){return this.map(function(){var a=n.prop(this,"elements");return a?n.makeArray(a):this}).filter(function(){var a=this.type;return this.name&&!n(this).is(":disabled")&&zb.test(this.nodeName)&&!yb.test(a)&&(this.checked||!T.test(a))}).map(function(a,b){var c=n(this).val();return null==c?null:n.isArray(c)?n.map(c,function(a){return{name:b.name,value:a.replace(xb,"\r\n")}}):{name:b.name,value:c.replace(xb,"\r\n")}}).get()}}),n.ajaxSettings.xhr=function(){try{return new XMLHttpRequest}catch(a){}};var Bb=0,Cb={},Db={0:200,1223:204},Eb=n.ajaxSettings.xhr();a.attachEvent&&a.attachEvent("onunload",function(){for(var a in Cb)Cb[a]()}),k.cors=!!Eb&&"withCredentials"in Eb,k.ajax=Eb=!!Eb,n.ajaxTransport(function(a){var b;return k.cors||Eb&&!a.crossDomain?{send:function(c,d){var e,f=a.xhr(),g=++Bb;if(f.open(a.type,a.url,a.async,a.username,a.password),a.xhrFields)for(e in a.xhrFields)f[e]=a.xhrFields[e];a.mimeType&&f.overrideMimeType&&f.overrideMimeType(a.mimeType),a.crossDomain||c["X-Requested-With"]||(c["X-Requested-With"]="XMLHttpRequest");for(e in c)f.setRequestHeader(e,c[e]);b=function(a){return function(){b&&(delete Cb[g],b=f.onload=f.onerror=null,"abort"===a?f.abort():"error"===a?d(f.status,f.statusText):d(Db[f.status]||f.status,f.statusText,"string"==typeof f.responseText?{text:f.responseText}:void 0,f.getAllResponseHeaders()))}},f.onload=b(),f.onerror=b("error"),b=Cb[g]=b("abort");try{f.send(a.hasContent&&a.data||null)}catch(h){if(b)throw h}},abort:function(){b&&b()}}:void 0}),n.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/(?:java|ecma)script/},converters:{"text script":function(a){return n.globalEval(a),a}}}),n.ajaxPrefilter("script",function(a){void 0===a.cache&&(a.cache=!1),a.crossDomain&&(a.type="GET")}),n.ajaxTransport("script",function(a){if(a.crossDomain){var b,c;return{send:function(d,e){b=n("<script>").prop({async:!0,charset:a.scriptCharset,src:a.url}).on("load error",c=function(a){b.remove(),c=null,a&&e("error"===a.type?404:200,a.type)}),l.head.appendChild(b[0])},abort:function(){c&&c()}}}});var Fb=[],Gb=/(=)\?(?=&|$)|\?\?/;n.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var a=Fb.pop()||n.expando+"_"+cb++;return this[a]=!0,a}}),n.ajaxPrefilter("json jsonp",function(b,c,d){var e,f,g,h=b.jsonp!==!1&&(Gb.test(b.url)?"url":"string"==typeof b.data&&!(b.contentType||"").indexOf("application/x-www-form-urlencoded")&&Gb.test(b.data)&&"data");return h||"jsonp"===b.dataTypes[0]?(e=b.jsonpCallback=n.isFunction(b.jsonpCallback)?b.jsonpCallback():b.jsonpCallback,h?b[h]=b[h].replace(Gb,"$1"+e):b.jsonp!==!1&&(b.url+=(db.test(b.url)?"&":"?")+b.jsonp+"="+e),b.converters["script json"]=function(){return g||n.error(e+" was not called"),g[0]},b.dataTypes[0]="json",f=a[e],a[e]=function(){g=arguments},d.always(function(){a[e]=f,b[e]&&(b.jsonpCallback=c.jsonpCallback,Fb.push(e)),g&&n.isFunction(f)&&f(g[0]),g=f=void 0}),"script"):void 0}),n.parseHTML=function(a,b,c){if(!a||"string"!=typeof a)return null;"boolean"==typeof b&&(c=b,b=!1),b=b||l;var d=v.exec(a),e=!c&&[];return d?[b.createElement(d[1])]:(d=n.buildFragment([a],b,e),e&&e.length&&n(e).remove(),n.merge([],d.childNodes))};var Hb=n.fn.load;n.fn.load=function(a,b,c){if("string"!=typeof a&&Hb)return Hb.apply(this,arguments);var d,e,f,g=this,h=a.indexOf(" ");return h>=0&&(d=n.trim(a.slice(h)),a=a.slice(0,h)),n.isFunction(b)?(c=b,b=void 0):b&&"object"==typeof b&&(e="POST"),g.length>0&&n.ajax({url:a,type:e,dataType:"html",data:b}).done(function(a){f=arguments,g.html(d?n("<div>").append(n.parseHTML(a)).find(d):a)}).complete(c&&function(a,b){g.each(c,f||[a.responseText,b,a])}),this},n.each(["ajaxStart","ajaxStop","ajaxComplete","ajaxError","ajaxSuccess","ajaxSend"],function(a,b){n.fn[b]=function(a){return this.on(b,a)}}),n.expr.filters.animated=function(a){return n.grep(n.timers,function(b){return a===b.elem}).length};var Ib=a.document.documentElement;function Jb(a){return n.isWindow(a)?a:9===a.nodeType&&a.defaultView}n.offset={setOffset:function(a,b,c){var d,e,f,g,h,i,j,k=n.css(a,"position"),l=n(a),m={};"static"===k&&(a.style.position="relative"),h=l.offset(),f=n.css(a,"top"),i=n.css(a,"left"),j=("absolute"===k||"fixed"===k)&&(f+i).indexOf("auto")>-1,j?(d=l.position(),g=d.top,e=d.left):(g=parseFloat(f)||0,e=parseFloat(i)||0),n.isFunction(b)&&(b=b.call(a,c,h)),null!=b.top&&(m.top=b.top-h.top+g),null!=b.left&&(m.left=b.left-h.left+e),"using"in b?b.using.call(a,m):l.css(m)}},n.fn.extend({offset:function(a){if(arguments.length)return void 0===a?this:this.each(function(b){n.offset.setOffset(this,a,b)});var b,c,d=this[0],e={top:0,left:0},f=d&&d.ownerDocument;if(f)return b=f.documentElement,n.contains(b,d)?(typeof d.getBoundingClientRect!==U&&(e=d.getBoundingClientRect()),c=Jb(f),{top:e.top+c.pageYOffset-b.clientTop,left:e.left+c.pageXOffset-b.clientLeft}):e},position:function(){if(this[0]){var a,b,c=this[0],d={top:0,left:0};return"fixed"===n.css(c,"position")?b=c.getBoundingClientRect():(a=this.offsetParent(),b=this.offset(),n.nodeName(a[0],"html")||(d=a.offset()),d.top+=n.css(a[0],"borderTopWidth",!0),d.left+=n.css(a[0],"borderLeftWidth",!0)),{top:b.top-d.top-n.css(c,"marginTop",!0),left:b.left-d.left-n.css(c,"marginLeft",!0)}}},offsetParent:function(){return this.map(function(){var a=this.offsetParent||Ib;while(a&&!n.nodeName(a,"html")&&"static"===n.css(a,"position"))a=a.offsetParent;return a||Ib})}}),n.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(b,c){var d="pageYOffset"===c;n.fn[b]=function(e){return J(this,function(b,e,f){var g=Jb(b);return void 0===f?g?g[c]:b[e]:void(g?g.scrollTo(d?a.pageXOffset:f,d?f:a.pageYOffset):b[e]=f)},b,e,arguments.length,null)}}),n.each(["top","left"],function(a,b){n.cssHooks[b]=ya(k.pixelPosition,function(a,c){return c?(c=xa(a,b),va.test(c)?n(a).position()[b]+"px":c):void 0})}),n.each({Height:"height",Width:"width"},function(a,b){n.each({padding:"inner"+a,content:b,"":"outer"+a},function(c,d){n.fn[d]=function(d,e){var f=arguments.length&&(c||"boolean"!=typeof d),g=c||(d===!0||e===!0?"margin":"border");return J(this,function(b,c,d){var e;return n.isWindow(b)?b.document.documentElement["client"+a]:9===b.nodeType?(e=b.documentElement,Math.max(b.body["scroll"+a],e["scroll"+a],b.body["offset"+a],e["offset"+a],e["client"+a])):void 0===d?n.css(b,c,g):n.style(b,c,d,g)},b,f?d:void 0,f,null)}})}),n.fn.size=function(){return this.length},n.fn.andSelf=n.fn.addBack,"function"==typeof define&&define.amd&&define("jquery",[],function(){return n});var Kb=a.jQuery,Lb=a.$;return n.noConflict=function(b){return a.$===n&&(a.$=Lb),b&&a.jQuery===n&&(a.jQuery=Kb),n},typeof b===U&&(a.jQuery=a.$=n),n});
//# sourceMappingURL=jquery.min.map
;
/**
 * 移动端富文本编辑器
 * @author ganzw@gmail.com
 * @url    https://github.com/baixuexiyang/artEditor
 */
$.fn.extend({_opt:{placeholader:"<p>请输入文章正文内容</p>",validHtml:[],limitSize:3,showServer:!1},artEditor:function(e){var t=this,a={"-webkit-user-select":"text","user-select":"text","overflow-y":"auto","text-break":"brak-all",outline:"none"};$(this).css(a).attr("contenteditable",!0),t._opt=$.extend(t._opt,e);try{$(t._opt.imgTar).on("change",function(e){var a=e.target.files[0];if(e.target.value="",Math.ceil(a.size/1024/1024)>t._opt.limitSize)return void console.error("文件太大");var r=new FileReader;r.readAsDataURL(a),r.onload=function(e){if(t._opt.showServer)return void t.upload(e.target.result);var a='<img src="'+e.target.result+'" style="width:90%;" />';t.insertImage(a)}}),t.placeholderHandler(),t.pasteHandler()}catch(r){console.log(r)}t._opt.formInputId&&$("#"+t._opt.formInputId)[0]&&$(t).on("input",function(){$("#"+t._opt.formInputId).val(t.getValue())})},upload:function(e){{var t=this;t._opt.uploadField}$.ajax({url:t._opt.uploadUrl,type:"post",data:$.extend(t._opt.data,{filed:e}),cache:!1}).then(function(e){var a=t._opt.uploadSuccess(e);if(a){var r='<img src="'+a+'" style="width:90%;" />';t.insertImage(r)}else t._opt.uploadError(e)})},insertImage:function(e){$(this).focus();var t=window.getSelection?window.getSelection():document.selection,a=t.createRange?t.createRange():t.getRangeAt(0);if(window.getSelection){a.collapse(!1);for(var r=a.createContextualFragment(e),o=r.lastChild;o&&"br"==o.nodeName.toLowerCase()&&o.previousSibling&&"br"==o.previousSibling.nodeName.toLowerCase();){var l=o;o=o.previousSibling,r.removeChild(l)}a.insertNode(a.createContextualFragment("<br/>")),a.insertNode(r),o&&(a.setEndAfter(o),a.setStartAfter(o)),t.removeAllRanges(),t.addRange(a)}else a.pasteHTML(e),a.collapse(!1),a.select();this._opt.formInputId&&$("#"+this._opt.formInputId)[0]&&$("#"+this._opt.formInputId).val(this.getValue())},pasteHandler:function(){var e=this;$(this).on("paste",function(t){console.log(t.clipboardData.items);var a=$(this).html();console.log(a),valiHTML=e._opt.validHtml,a=a.replace(/_moz_dirty=""/gi,"").replace(/\[/g,"[[-").replace(/\]/g,"-]]").replace(/<\/ ?tr[^>]*>/gi,"[br]").replace(/<\/ ?td[^>]*>/gi,"&nbsp;&nbsp;").replace(/<(ul|dl|ol)[^>]*>/gi,"[br]").replace(/<(li|dd)[^>]*>/gi,"[br]").replace(/<p [^>]*>/gi,"[br]").replace(new RegExp("<(/?(?:"+valiHTML.join("|")+")[^>]*)>","gi"),"[$1]").replace(new RegExp('<span([^>]*class="?at"?[^>]*)>',"gi"),"[span$1]").replace(/<[^>]*>/g,"").replace(/\[\[\-/g,"[").replace(/\-\]\]/g,"]").replace(new RegExp("\\[(/?(?:"+valiHTML.join("|")+"|img|span)[^\\]]*)\\]","gi"),"<$1>"),/firefox/.test(navigator.userAgent.toLowerCase())||(a=a.replace(/\r?\n/gi,"<br>")),$(this).html(a)})},placeholderHandler:function(){var e=this;$(this).on("focus",function(){$.trim($(this).html())===e._opt.placeholader&&$(this).html("")}).on("blur",function(){$(this).html()||$(this).html(e._opt.placeholader)}),$.trim($(this).html())||$(this).html(e._opt.placeholader)},getValue:function(){return $(this).html()},setValue:function(e){$(this).html(e)}});

;
/*!
 * Cropper v2.3.2
 * https://github.com/fengyuanchen/cropper
 *
 * Copyright (c) 2014-2016 Fengyuan Chen and contributors
 * Released under the MIT license
 *
 * Date: 2016-06-08T12:14:46.286Z
 */
!function(t){"function"==typeof define&&define.amd?define(["jquery"],t):t("object"==typeof exports?require("jquery"):jQuery)}(function(t){"use strict";function i(t){return"number"==typeof t&&!isNaN(t)}function e(t){return"undefined"==typeof t}function s(t,e){var s=[];return i(e)&&s.push(e),s.slice.apply(t,s)}function a(t,i){var e=s(arguments,2);return function(){return t.apply(i,e.concat(s(arguments)))}}function o(t){var i=t.match(/^(https?:)\/\/([^\:\/\?#]+):?(\d*)/i);return i&&(i[1]!==C.protocol||i[2]!==C.hostname||i[3]!==C.port)}function h(t){var i="timestamp="+(new Date).getTime();return t+(-1===t.indexOf("?")?"?":"&")+i}function n(t){return t?' crossOrigin="'+t+'"':""}function r(t,i){var e;return t.naturalWidth&&!mt?i(t.naturalWidth,t.naturalHeight):(e=document.createElement("img"),e.onload=function(){i(this.width,this.height)},void(e.src=t.src))}function p(t){var e=[],s=t.rotate,a=t.scaleX,o=t.scaleY;return i(a)&&i(o)&&e.push("scale("+a+","+o+")"),i(s)&&e.push("rotate("+s+"deg)"),e.length?e.join(" "):"none"}function c(t,i){var e,s,a=Ct(t.degree)%180,o=(a>90?180-a:a)*Math.PI/180,h=bt(o),n=Bt(o),r=t.width,p=t.height,c=t.aspectRatio;return i?(e=r/(n+h/c),s=e/c):(e=r*n+p*h,s=r*h+p*n),{width:e,height:s}}function l(e,s){var a,o,h,n=t("<canvas>")[0],r=n.getContext("2d"),p=0,l=0,d=s.naturalWidth,g=s.naturalHeight,u=s.rotate,f=s.scaleX,m=s.scaleY,v=i(f)&&i(m)&&(1!==f||1!==m),w=i(u)&&0!==u,x=w||v,C=d*Ct(f||1),b=g*Ct(m||1);return v&&(a=C/2,o=b/2),w&&(h=c({width:C,height:b,degree:u}),C=h.width,b=h.height,a=C/2,o=b/2),n.width=C,n.height=b,x&&(p=-d/2,l=-g/2,r.save(),r.translate(a,o)),v&&r.scale(f,m),w&&r.rotate(u*Math.PI/180),r.drawImage(e,$t(p),$t(l),$t(d),$t(g)),x&&r.restore(),n}function d(i){var e=i.length,s=0,a=0;return e&&(t.each(i,function(t,i){s+=i.pageX,a+=i.pageY}),s/=e,a/=e),{pageX:s,pageY:a}}function g(t,i,e){var s,a="";for(s=i,e+=i;e>s;s++)a+=Lt(t.getUint8(s));return a}function u(t){var i,e,s,a,o,h,n,r,p,c,l=new D(t),d=l.byteLength;if(255===l.getUint8(0)&&216===l.getUint8(1))for(p=2;d>p;){if(255===l.getUint8(p)&&225===l.getUint8(p+1)){n=p;break}p++}if(n&&(e=n+4,s=n+10,"Exif"===g(l,e,4)&&(h=l.getUint16(s),o=18761===h,(o||19789===h)&&42===l.getUint16(s+2,o)&&(a=l.getUint32(s+4,o),a>=8&&(r=s+a)))),r)for(d=l.getUint16(r,o),c=0;d>c;c++)if(p=r+12*c+2,274===l.getUint16(p,o)){p+=8,i=l.getUint16(p,o),mt&&l.setUint16(p,1,o);break}return i}function f(t){var i,e=t.replace(G,""),s=atob(e),a=s.length,o=new B(a),h=new y(o);for(i=0;a>i;i++)h[i]=s.charCodeAt(i);return o}function m(t){var i,e=new y(t),s=e.length,a="";for(i=0;s>i;i++)a+=Lt(e[i]);return"data:image/jpeg;base64,"+$(a)}function v(i,e){this.$element=t(i),this.options=t.extend({},v.DEFAULTS,t.isPlainObject(e)&&e),this.isLoaded=!1,this.isBuilt=!1,this.isCompleted=!1,this.isRotated=!1,this.isCropped=!1,this.isDisabled=!1,this.isReplaced=!1,this.isLimited=!1,this.wheeling=!1,this.isImg=!1,this.originalUrl="",this.canvas=null,this.cropBox=null,this.init()}var w=t(window),x=t(document),C=window.location,b=window.navigator,B=window.ArrayBuffer,y=window.Uint8Array,D=window.DataView,$=window.btoa,L="cropper",T="cropper-modal",X="cropper-hide",Y="cropper-hidden",k="cropper-invisible",M="cropper-move",W="cropper-crop",H="cropper-disabled",R="cropper-bg",z="mousedown touchstart pointerdown MSPointerDown",O="mousemove touchmove pointermove MSPointerMove",P="mouseup touchend touchcancel pointerup pointercancel MSPointerUp MSPointerCancel",E="wheel mousewheel DOMMouseScroll",U="dblclick",I="load."+L,F="error."+L,j="resize."+L,A="build."+L,S="built."+L,N="cropstart."+L,_="cropmove."+L,q="cropend."+L,K="crop."+L,Z="zoom."+L,Q=/e|w|s|n|se|sw|ne|nw|all|crop|move|zoom/,V=/^data\:/,G=/^data\:([^\;]+)\;base64,/,J=/^data\:image\/jpeg.*;base64,/,tt="preview",it="action",et="e",st="w",at="s",ot="n",ht="se",nt="sw",rt="ne",pt="nw",ct="all",lt="crop",dt="move",gt="zoom",ut="none",ft=t.isFunction(t("<canvas>")[0].getContext),mt=b&&/(Macintosh|iPhone|iPod|iPad).*AppleWebKit/i.test(b.userAgent),vt=Number,wt=Math.min,xt=Math.max,Ct=Math.abs,bt=Math.sin,Bt=Math.cos,yt=Math.sqrt,Dt=Math.round,$t=Math.floor,Lt=String.fromCharCode;v.prototype={constructor:v,init:function(){var t,i=this.$element;if(i.is("img")){if(this.isImg=!0,this.originalUrl=t=i.attr("src"),!t)return;t=i.prop("src")}else i.is("canvas")&&ft&&(t=i[0].toDataURL());this.load(t)},trigger:function(i,e){var s=t.Event(i,e);return this.$element.trigger(s),s},load:function(i){var e,s,a=this.options,n=this.$element;if(i&&(n.one(A,a.build),!this.trigger(A).isDefaultPrevented())){if(this.url=i,this.image={},!a.checkOrientation||!B)return this.clone();if(e=t.proxy(this.read,this),V.test(i))return J.test(i)?e(f(i)):this.clone();s=new XMLHttpRequest,s.onerror=s.onabort=t.proxy(function(){this.clone()},this),s.onload=function(){e(this.response)},a.checkCrossOrigin&&o(i)&&n.prop("crossOrigin")&&(i=h(i)),s.open("get",i),s.responseType="arraybuffer",s.send()}},read:function(t){var i,e,s,a=this.options,o=u(t),h=this.image;if(o>1)switch(this.url=m(t),o){case 2:e=-1;break;case 3:i=-180;break;case 4:s=-1;break;case 5:i=90,s=-1;break;case 6:i=90;break;case 7:i=90,e=-1;break;case 8:i=-90}a.rotatable&&(h.rotate=i),a.scalable&&(h.scaleX=e,h.scaleY=s),this.clone()},clone:function(){var i,e,s=this.options,a=this.$element,r=this.url,p="";s.checkCrossOrigin&&o(r)&&(p=a.prop("crossOrigin"),p?i=r:(p="anonymous",i=h(r))),this.crossOrigin=p,this.crossOriginUrl=i,this.$clone=e=t("<img"+n(p)+' src="'+(i||r)+'">'),this.isImg?a[0].complete?this.start():a.one(I,t.proxy(this.start,this)):e.one(I,t.proxy(this.start,this)).one(F,t.proxy(this.stop,this)).addClass(X).insertAfter(a)},start:function(){var i=this.$element,e=this.$clone;this.isImg||(e.off(F,this.stop),i=e),r(i[0],t.proxy(function(i,e){t.extend(this.image,{naturalWidth:i,naturalHeight:e,aspectRatio:i/e}),this.isLoaded=!0,this.build()},this))},stop:function(){this.$clone.remove(),this.$clone=null},build:function(){var i,e,s,a=this.options,o=this.$element,h=this.$clone;this.isLoaded&&(this.isBuilt&&this.unbuild(),this.$container=o.parent(),this.$cropper=i=t(v.TEMPLATE),this.$canvas=i.find(".cropper-canvas").append(h),this.$dragBox=i.find(".cropper-drag-box"),this.$cropBox=e=i.find(".cropper-crop-box"),this.$viewBox=i.find(".cropper-view-box"),this.$face=s=e.find(".cropper-face"),o.addClass(Y).after(i),this.isImg||h.removeClass(X),this.initPreview(),this.bind(),a.aspectRatio=xt(0,a.aspectRatio)||NaN,a.viewMode=xt(0,wt(3,Dt(a.viewMode)))||0,a.autoCrop?(this.isCropped=!0,a.modal&&this.$dragBox.addClass(T)):e.addClass(Y),a.guides||e.find(".cropper-dashed").addClass(Y),a.center||e.find(".cropper-center").addClass(Y),a.cropBoxMovable&&s.addClass(M).data(it,ct),a.highlight||s.addClass(k),a.background&&i.addClass(R),a.cropBoxResizable||e.find(".cropper-line, .cropper-point").addClass(Y),this.setDragMode(a.dragMode),this.render(),this.isBuilt=!0,this.setData(a.data),o.one(S,a.built),setTimeout(t.proxy(function(){this.trigger(S),this.trigger(K,this.getData()),this.isCompleted=!0},this),0))},unbuild:function(){this.isBuilt&&(this.isBuilt=!1,this.isCompleted=!1,this.initialImage=null,this.initialCanvas=null,this.initialCropBox=null,this.container=null,this.canvas=null,this.cropBox=null,this.unbind(),this.resetPreview(),this.$preview=null,this.$viewBox=null,this.$cropBox=null,this.$dragBox=null,this.$canvas=null,this.$container=null,this.$cropper.remove(),this.$cropper=null)},render:function(){this.initContainer(),this.initCanvas(),this.initCropBox(),this.renderCanvas(),this.isCropped&&this.renderCropBox()},initContainer:function(){var t=this.options,i=this.$element,e=this.$container,s=this.$cropper;s.addClass(Y),i.removeClass(Y),s.css(this.container={width:xt(e.width(),vt(t.minContainerWidth)||200),height:xt(e.height(),vt(t.minContainerHeight)||100)}),i.addClass(Y),s.removeClass(Y)},initCanvas:function(){var i,e=this.options.viewMode,s=this.container,a=s.width,o=s.height,h=this.image,n=h.naturalWidth,r=h.naturalHeight,p=90===Ct(h.rotate),c=p?r:n,l=p?n:r,d=c/l,g=a,u=o;o*d>a?3===e?g=o*d:u=a/d:3===e?u=a/d:g=o*d,i={naturalWidth:c,naturalHeight:l,aspectRatio:d,width:g,height:u},i.oldLeft=i.left=(a-g)/2,i.oldTop=i.top=(o-u)/2,this.canvas=i,this.isLimited=1===e||2===e,this.limitCanvas(!0,!0),this.initialImage=t.extend({},h),this.initialCanvas=t.extend({},i)},limitCanvas:function(t,i){var e,s,a,o,h=this.options,n=h.viewMode,r=this.container,p=r.width,c=r.height,l=this.canvas,d=l.aspectRatio,g=this.cropBox,u=this.isCropped&&g;t&&(e=vt(h.minCanvasWidth)||0,s=vt(h.minCanvasHeight)||0,n&&(n>1?(e=xt(e,p),s=xt(s,c),3===n&&(s*d>e?e=s*d:s=e/d)):e?e=xt(e,u?g.width:0):s?s=xt(s,u?g.height:0):u&&(e=g.width,s=g.height,s*d>e?e=s*d:s=e/d)),e&&s?s*d>e?s=e/d:e=s*d:e?s=e/d:s&&(e=s*d),l.minWidth=e,l.minHeight=s,l.maxWidth=1/0,l.maxHeight=1/0),i&&(n?(a=p-l.width,o=c-l.height,l.minLeft=wt(0,a),l.minTop=wt(0,o),l.maxLeft=xt(0,a),l.maxTop=xt(0,o),u&&this.isLimited&&(l.minLeft=wt(g.left,g.left+g.width-l.width),l.minTop=wt(g.top,g.top+g.height-l.height),l.maxLeft=g.left,l.maxTop=g.top,2===n&&(l.width>=p&&(l.minLeft=wt(0,a),l.maxLeft=xt(0,a)),l.height>=c&&(l.minTop=wt(0,o),l.maxTop=xt(0,o))))):(l.minLeft=-l.width,l.minTop=-l.height,l.maxLeft=p,l.maxTop=c))},renderCanvas:function(t){var i,e,s=this.canvas,a=this.image,o=a.rotate,h=a.naturalWidth,n=a.naturalHeight;this.isRotated&&(this.isRotated=!1,e=c({width:a.width,height:a.height,degree:o}),i=e.width/e.height,i!==s.aspectRatio&&(s.left-=(e.width-s.width)/2,s.top-=(e.height-s.height)/2,s.width=e.width,s.height=e.height,s.aspectRatio=i,s.naturalWidth=h,s.naturalHeight=n,o%180&&(e=c({width:h,height:n,degree:o}),s.naturalWidth=e.width,s.naturalHeight=e.height),this.limitCanvas(!0,!1))),(s.width>s.maxWidth||s.width<s.minWidth)&&(s.left=s.oldLeft),(s.height>s.maxHeight||s.height<s.minHeight)&&(s.top=s.oldTop),s.width=wt(xt(s.width,s.minWidth),s.maxWidth),s.height=wt(xt(s.height,s.minHeight),s.maxHeight),this.limitCanvas(!1,!0),s.oldLeft=s.left=wt(xt(s.left,s.minLeft),s.maxLeft),s.oldTop=s.top=wt(xt(s.top,s.minTop),s.maxTop),this.$canvas.css({width:s.width,height:s.height,left:s.left,top:s.top}),this.renderImage(),this.isCropped&&this.isLimited&&this.limitCropBox(!0,!0),t&&this.output()},renderImage:function(i){var e,s=this.canvas,a=this.image;a.rotate&&(e=c({width:s.width,height:s.height,degree:a.rotate,aspectRatio:a.aspectRatio},!0)),t.extend(a,e?{width:e.width,height:e.height,left:(s.width-e.width)/2,top:(s.height-e.height)/2}:{width:s.width,height:s.height,left:0,top:0}),this.$clone.css({width:a.width,height:a.height,marginLeft:a.left,marginTop:a.top,transform:p(a)}),i&&this.output()},initCropBox:function(){var i=this.options,e=this.canvas,s=i.aspectRatio,a=vt(i.autoCropArea)||.8,o={width:e.width,height:e.height};s&&(e.height*s>e.width?o.height=o.width/s:o.width=o.height*s),this.cropBox=o,this.limitCropBox(!0,!0),o.width=wt(xt(o.width,o.minWidth),o.maxWidth),o.height=wt(xt(o.height,o.minHeight),o.maxHeight),o.width=xt(o.minWidth,o.width*a),o.height=xt(o.minHeight,o.height*a),o.oldLeft=o.left=e.left+(e.width-o.width)/2,o.oldTop=o.top=e.top+(e.height-o.height)/2,this.initialCropBox=t.extend({},o)},limitCropBox:function(t,i){var e,s,a,o,h=this.options,n=h.aspectRatio,r=this.container,p=r.width,c=r.height,l=this.canvas,d=this.cropBox,g=this.isLimited;t&&(e=vt(h.minCropBoxWidth)||0,s=vt(h.minCropBoxHeight)||0,e=wt(e,p),s=wt(s,c),a=wt(p,g?l.width:p),o=wt(c,g?l.height:c),n&&(e&&s?s*n>e?s=e/n:e=s*n:e?s=e/n:s&&(e=s*n),o*n>a?o=a/n:a=o*n),d.minWidth=wt(e,a),d.minHeight=wt(s,o),d.maxWidth=a,d.maxHeight=o),i&&(g?(d.minLeft=xt(0,l.left),d.minTop=xt(0,l.top),d.maxLeft=wt(p,l.left+l.width)-d.width,d.maxTop=wt(c,l.top+l.height)-d.height):(d.minLeft=0,d.minTop=0,d.maxLeft=p-d.width,d.maxTop=c-d.height))},renderCropBox:function(){var t=this.options,i=this.container,e=i.width,s=i.height,a=this.cropBox;(a.width>a.maxWidth||a.width<a.minWidth)&&(a.left=a.oldLeft),(a.height>a.maxHeight||a.height<a.minHeight)&&(a.top=a.oldTop),a.width=wt(xt(a.width,a.minWidth),a.maxWidth),a.height=wt(xt(a.height,a.minHeight),a.maxHeight),this.limitCropBox(!1,!0),a.oldLeft=a.left=wt(xt(a.left,a.minLeft),a.maxLeft),a.oldTop=a.top=wt(xt(a.top,a.minTop),a.maxTop),t.movable&&t.cropBoxMovable&&this.$face.data(it,a.width===e&&a.height===s?dt:ct),this.$cropBox.css({width:a.width,height:a.height,left:a.left,top:a.top}),this.isCropped&&this.isLimited&&this.limitCanvas(!0,!0),this.isDisabled||this.output()},output:function(){this.preview(),this.isCompleted&&this.trigger(K,this.getData())},initPreview:function(){var i,e=n(this.crossOrigin),s=e?this.crossOriginUrl:this.url;this.$preview=t(this.options.preview),this.$clone2=i=t("<img"+e+' src="'+s+'">'),this.$viewBox.html(i),this.$preview.each(function(){var i=t(this);i.data(tt,{width:i.width(),height:i.height(),html:i.html()}),i.html("<img"+e+' src="'+s+'" style="display:block;width:100%;height:auto;min-width:0!important;min-height:0!important;max-width:none!important;max-height:none!important;image-orientation:0deg!important;">')})},resetPreview:function(){this.$preview.each(function(){var i=t(this),e=i.data(tt);i.css({width:e.width,height:e.height}).html(e.html).removeData(tt)})},preview:function(){var i=this.image,e=this.canvas,s=this.cropBox,a=s.width,o=s.height,h=i.width,n=i.height,r=s.left-e.left-i.left,c=s.top-e.top-i.top;this.isCropped&&!this.isDisabled&&(this.$clone2.css({width:h,height:n,marginLeft:-r,marginTop:-c,transform:p(i)}),this.$preview.each(function(){var e=t(this),s=e.data(tt),l=s.width,d=s.height,g=l,u=d,f=1;a&&(f=l/a,u=o*f),o&&u>d&&(f=d/o,g=a*f,u=d),e.css({width:g,height:u}).find("img").css({width:h*f,height:n*f,marginLeft:-r*f,marginTop:-c*f,transform:p(i)})}))},bind:function(){var i=this.options,e=this.$element,s=this.$cropper;t.isFunction(i.cropstart)&&e.on(N,i.cropstart),t.isFunction(i.cropmove)&&e.on(_,i.cropmove),t.isFunction(i.cropend)&&e.on(q,i.cropend),t.isFunction(i.crop)&&e.on(K,i.crop),t.isFunction(i.zoom)&&e.on(Z,i.zoom),s.on(z,t.proxy(this.cropStart,this)),i.zoomable&&i.zoomOnWheel&&s.on(E,t.proxy(this.wheel,this)),i.toggleDragModeOnDblclick&&s.on(U,t.proxy(this.dblclick,this)),x.on(O,this._cropMove=a(this.cropMove,this)).on(P,this._cropEnd=a(this.cropEnd,this)),i.responsive&&w.on(j,this._resize=a(this.resize,this))},unbind:function(){var i=this.options,e=this.$element,s=this.$cropper;t.isFunction(i.cropstart)&&e.off(N,i.cropstart),t.isFunction(i.cropmove)&&e.off(_,i.cropmove),t.isFunction(i.cropend)&&e.off(q,i.cropend),t.isFunction(i.crop)&&e.off(K,i.crop),t.isFunction(i.zoom)&&e.off(Z,i.zoom),s.off(z,this.cropStart),i.zoomable&&i.zoomOnWheel&&s.off(E,this.wheel),i.toggleDragModeOnDblclick&&s.off(U,this.dblclick),x.off(O,this._cropMove).off(P,this._cropEnd),i.responsive&&w.off(j,this._resize)},resize:function(){var i,e,s,a=this.options.restore,o=this.$container,h=this.container;!this.isDisabled&&h&&(s=o.width()/h.width,1===s&&o.height()===h.height||(a&&(i=this.getCanvasData(),e=this.getCropBoxData()),this.render(),a&&(this.setCanvasData(t.each(i,function(t,e){i[t]=e*s})),this.setCropBoxData(t.each(e,function(t,i){e[t]=i*s})))))},dblclick:function(){this.isDisabled||(this.$dragBox.hasClass(W)?this.setDragMode(dt):this.setDragMode(lt))},wheel:function(i){var e=i.originalEvent||i,s=vt(this.options.wheelZoomRatio)||.1,a=1;this.isDisabled||(i.preventDefault(),this.wheeling||(this.wheeling=!0,setTimeout(t.proxy(function(){this.wheeling=!1},this),50),e.deltaY?a=e.deltaY>0?1:-1:e.wheelDelta?a=-e.wheelDelta/120:e.detail&&(a=e.detail>0?1:-1),this.zoom(-a*s,i)))},cropStart:function(i){var e,s,a=this.options,o=i.originalEvent,h=o&&o.touches,n=i;if(!this.isDisabled){if(h){if(e=h.length,e>1){if(!a.zoomable||!a.zoomOnTouch||2!==e)return;n=h[1],this.startX2=n.pageX,this.startY2=n.pageY,s=gt}n=h[0]}if(s=s||t(n.target).data(it),Q.test(s)){if(this.trigger(N,{originalEvent:o,action:s}).isDefaultPrevented())return;i.preventDefault(),this.action=s,this.cropping=!1,this.startX=n.pageX||o&&o.pageX,this.startY=n.pageY||o&&o.pageY,s===lt&&(this.cropping=!0,this.$dragBox.addClass(T))}}},cropMove:function(t){var i,e=this.options,s=t.originalEvent,a=s&&s.touches,o=t,h=this.action;if(!this.isDisabled){if(a){if(i=a.length,i>1){if(!e.zoomable||!e.zoomOnTouch||2!==i)return;o=a[1],this.endX2=o.pageX,this.endY2=o.pageY}o=a[0]}if(h){if(this.trigger(_,{originalEvent:s,action:h}).isDefaultPrevented())return;t.preventDefault(),this.endX=o.pageX||s&&s.pageX,this.endY=o.pageY||s&&s.pageY,this.change(o.shiftKey,h===gt?t:null)}}},cropEnd:function(t){var i=t.originalEvent,e=this.action;this.isDisabled||e&&(t.preventDefault(),this.cropping&&(this.cropping=!1,this.$dragBox.toggleClass(T,this.isCropped&&this.options.modal)),this.action="",this.trigger(q,{originalEvent:i,action:e}))},change:function(t,i){var e,s,a=this.options,o=a.aspectRatio,h=this.action,n=this.container,r=this.canvas,p=this.cropBox,c=p.width,l=p.height,d=p.left,g=p.top,u=d+c,f=g+l,m=0,v=0,w=n.width,x=n.height,C=!0;switch(!o&&t&&(o=c&&l?c/l:1),this.isLimited&&(m=p.minLeft,v=p.minTop,w=m+wt(n.width,r.left+r.width),x=v+wt(n.height,r.top+r.height)),s={x:this.endX-this.startX,y:this.endY-this.startY},o&&(s.X=s.y*o,s.Y=s.x/o),h){case ct:d+=s.x,g+=s.y;break;case et:if(s.x>=0&&(u>=w||o&&(v>=g||f>=x))){C=!1;break}c+=s.x,o&&(l=c/o,g-=s.Y/2),0>c&&(h=st,c=0);break;case ot:if(s.y<=0&&(v>=g||o&&(m>=d||u>=w))){C=!1;break}l-=s.y,g+=s.y,o&&(c=l*o,d+=s.X/2),0>l&&(h=at,l=0);break;case st:if(s.x<=0&&(m>=d||o&&(v>=g||f>=x))){C=!1;break}c-=s.x,d+=s.x,o&&(l=c/o,g+=s.Y/2),0>c&&(h=et,c=0);break;case at:if(s.y>=0&&(f>=x||o&&(m>=d||u>=w))){C=!1;break}l+=s.y,o&&(c=l*o,d-=s.X/2),0>l&&(h=ot,l=0);break;case rt:if(o){if(s.y<=0&&(v>=g||u>=w)){C=!1;break}l-=s.y,g+=s.y,c=l*o}else s.x>=0?w>u?c+=s.x:s.y<=0&&v>=g&&(C=!1):c+=s.x,s.y<=0?g>v&&(l-=s.y,g+=s.y):(l-=s.y,g+=s.y);0>c&&0>l?(h=nt,l=0,c=0):0>c?(h=pt,c=0):0>l&&(h=ht,l=0);break;case pt:if(o){if(s.y<=0&&(v>=g||m>=d)){C=!1;break}l-=s.y,g+=s.y,c=l*o,d+=s.X}else s.x<=0?d>m?(c-=s.x,d+=s.x):s.y<=0&&v>=g&&(C=!1):(c-=s.x,d+=s.x),s.y<=0?g>v&&(l-=s.y,g+=s.y):(l-=s.y,g+=s.y);0>c&&0>l?(h=ht,l=0,c=0):0>c?(h=rt,c=0):0>l&&(h=nt,l=0);break;case nt:if(o){if(s.x<=0&&(m>=d||f>=x)){C=!1;break}c-=s.x,d+=s.x,l=c/o}else s.x<=0?d>m?(c-=s.x,d+=s.x):s.y>=0&&f>=x&&(C=!1):(c-=s.x,d+=s.x),s.y>=0?x>f&&(l+=s.y):l+=s.y;0>c&&0>l?(h=rt,l=0,c=0):0>c?(h=ht,c=0):0>l&&(h=pt,l=0);break;case ht:if(o){if(s.x>=0&&(u>=w||f>=x)){C=!1;break}c+=s.x,l=c/o}else s.x>=0?w>u?c+=s.x:s.y>=0&&f>=x&&(C=!1):c+=s.x,s.y>=0?x>f&&(l+=s.y):l+=s.y;0>c&&0>l?(h=pt,l=0,c=0):0>c?(h=nt,c=0):0>l&&(h=rt,l=0);break;case dt:this.move(s.x,s.y),C=!1;break;case gt:this.zoom(function(t,i,e,s){var a=yt(t*t+i*i),o=yt(e*e+s*s);return(o-a)/a}(Ct(this.startX-this.startX2),Ct(this.startY-this.startY2),Ct(this.endX-this.endX2),Ct(this.endY-this.endY2)),i),this.startX2=this.endX2,this.startY2=this.endY2,C=!1;break;case lt:if(!s.x||!s.y){C=!1;break}e=this.$cropper.offset(),d=this.startX-e.left,g=this.startY-e.top,c=p.minWidth,l=p.minHeight,s.x>0?h=s.y>0?ht:rt:s.x<0&&(d-=c,h=s.y>0?nt:pt),s.y<0&&(g-=l),this.isCropped||(this.$cropBox.removeClass(Y),this.isCropped=!0,this.isLimited&&this.limitCropBox(!0,!0))}C&&(p.width=c,p.height=l,p.left=d,p.top=g,this.action=h,this.renderCropBox()),this.startX=this.endX,this.startY=this.endY},crop:function(){this.isBuilt&&!this.isDisabled&&(this.isCropped||(this.isCropped=!0,this.limitCropBox(!0,!0),this.options.modal&&this.$dragBox.addClass(T),this.$cropBox.removeClass(Y)),this.setCropBoxData(this.initialCropBox))},reset:function(){this.isBuilt&&!this.isDisabled&&(this.image=t.extend({},this.initialImage),this.canvas=t.extend({},this.initialCanvas),this.cropBox=t.extend({},this.initialCropBox),this.renderCanvas(),this.isCropped&&this.renderCropBox())},clear:function(){this.isCropped&&!this.isDisabled&&(t.extend(this.cropBox,{left:0,top:0,width:0,height:0}),this.isCropped=!1,this.renderCropBox(),this.limitCanvas(!0,!0),this.renderCanvas(),this.$dragBox.removeClass(T),this.$cropBox.addClass(Y))},replace:function(t,i){!this.isDisabled&&t&&(this.isImg&&this.$element.attr("src",t),i?(this.url=t,this.$clone.attr("src",t),this.isBuilt&&this.$preview.find("img").add(this.$clone2).attr("src",t)):(this.isImg&&(this.isReplaced=!0),this.options.data=null,this.load(t)))},enable:function(){this.isBuilt&&(this.isDisabled=!1,this.$cropper.removeClass(H))},disable:function(){this.isBuilt&&(this.isDisabled=!0,this.$cropper.addClass(H))},destroy:function(){var t=this.$element;this.isLoaded?(this.isImg&&this.isReplaced&&t.attr("src",this.originalUrl),this.unbuild(),t.removeClass(Y)):this.isImg?t.off(I,this.start):this.$clone&&this.$clone.remove(),t.removeData(L)},move:function(t,i){var s=this.canvas;this.moveTo(e(t)?t:s.left+vt(t),e(i)?i:s.top+vt(i))},moveTo:function(t,s){var a=this.canvas,o=!1;e(s)&&(s=t),t=vt(t),s=vt(s),this.isBuilt&&!this.isDisabled&&this.options.movable&&(i(t)&&(a.left=t,o=!0),i(s)&&(a.top=s,o=!0),o&&this.renderCanvas(!0))},zoom:function(t,i){var e=this.canvas;t=vt(t),t=0>t?1/(1-t):1+t,this.zoomTo(e.width*t/e.naturalWidth,i)},zoomTo:function(t,i){var e,s,a,o,h,n=this.options,r=this.canvas,p=r.width,c=r.height,l=r.naturalWidth,g=r.naturalHeight;if(t=vt(t),t>=0&&this.isBuilt&&!this.isDisabled&&n.zoomable){if(s=l*t,a=g*t,i&&(e=i.originalEvent),this.trigger(Z,{originalEvent:e,oldRatio:p/l,ratio:s/l}).isDefaultPrevented())return;e?(o=this.$cropper.offset(),h=e.touches?d(e.touches):{pageX:i.pageX||e.pageX||0,pageY:i.pageY||e.pageY||0},r.left-=(s-p)*((h.pageX-o.left-r.left)/p),r.top-=(a-c)*((h.pageY-o.top-r.top)/c)):(r.left-=(s-p)/2,r.top-=(a-c)/2),r.width=s,r.height=a,this.renderCanvas(!0)}},rotate:function(t){this.rotateTo((this.image.rotate||0)+vt(t))},rotateTo:function(t){t=vt(t),i(t)&&this.isBuilt&&!this.isDisabled&&this.options.rotatable&&(this.image.rotate=t%360,this.isRotated=!0,this.renderCanvas(!0))},scale:function(t,s){var a=this.image,o=!1;e(s)&&(s=t),t=vt(t),s=vt(s),this.isBuilt&&!this.isDisabled&&this.options.scalable&&(i(t)&&(a.scaleX=t,o=!0),i(s)&&(a.scaleY=s,o=!0),o&&this.renderImage(!0))},scaleX:function(t){var e=this.image.scaleY;this.scale(t,i(e)?e:1)},scaleY:function(t){var e=this.image.scaleX;this.scale(i(e)?e:1,t)},getData:function(i){var e,s,a=this.options,o=this.image,h=this.canvas,n=this.cropBox;return this.isBuilt&&this.isCropped?(s={x:n.left-h.left,y:n.top-h.top,width:n.width,height:n.height},e=o.width/o.naturalWidth,t.each(s,function(t,a){a/=e,s[t]=i?Dt(a):a})):s={x:0,y:0,width:0,height:0},a.rotatable&&(s.rotate=o.rotate||0),a.scalable&&(s.scaleX=o.scaleX||1,s.scaleY=o.scaleY||1),s},setData:function(e){var s,a,o,h=this.options,n=this.image,r=this.canvas,p={};t.isFunction(e)&&(e=e.call(this.element)),this.isBuilt&&!this.isDisabled&&t.isPlainObject(e)&&(h.rotatable&&i(e.rotate)&&e.rotate!==n.rotate&&(n.rotate=e.rotate,this.isRotated=s=!0),h.scalable&&(i(e.scaleX)&&e.scaleX!==n.scaleX&&(n.scaleX=e.scaleX,a=!0),i(e.scaleY)&&e.scaleY!==n.scaleY&&(n.scaleY=e.scaleY,a=!0)),s?this.renderCanvas():a&&this.renderImage(),o=n.width/n.naturalWidth,i(e.x)&&(p.left=e.x*o+r.left),i(e.y)&&(p.top=e.y*o+r.top),i(e.width)&&(p.width=e.width*o),i(e.height)&&(p.height=e.height*o),this.setCropBoxData(p))},getContainerData:function(){return this.isBuilt?this.container:{}},getImageData:function(){return this.isLoaded?this.image:{}},getCanvasData:function(){var i=this.canvas,e={};return this.isBuilt&&t.each(["left","top","width","height","naturalWidth","naturalHeight"],function(t,s){e[s]=i[s]}),e},setCanvasData:function(e){var s=this.canvas,a=s.aspectRatio;t.isFunction(e)&&(e=e.call(this.$element)),this.isBuilt&&!this.isDisabled&&t.isPlainObject(e)&&(i(e.left)&&(s.left=e.left),i(e.top)&&(s.top=e.top),i(e.width)?(s.width=e.width,s.height=e.width/a):i(e.height)&&(s.height=e.height,s.width=e.height*a),this.renderCanvas(!0))},getCropBoxData:function(){var t,i=this.cropBox;return this.isBuilt&&this.isCropped&&(t={left:i.left,top:i.top,width:i.width,height:i.height}),t||{}},setCropBoxData:function(e){var s,a,o=this.cropBox,h=this.options.aspectRatio;t.isFunction(e)&&(e=e.call(this.$element)),this.isBuilt&&this.isCropped&&!this.isDisabled&&t.isPlainObject(e)&&(i(e.left)&&(o.left=e.left),i(e.top)&&(o.top=e.top),i(e.width)&&(s=!0,o.width=e.width),i(e.height)&&(a=!0,o.height=e.height),h&&(s?o.height=o.width/h:a&&(o.width=o.height*h)),this.renderCropBox())},getCroppedCanvas:function(i){var e,s,a,o,h,n,r,p,c,d,g;return this.isBuilt&&ft?this.isCropped?(t.isPlainObject(i)||(i={}),g=this.getData(),e=g.width,s=g.height,p=e/s,t.isPlainObject(i)&&(h=i.width,n=i.height,h?(n=h/p,r=h/e):n&&(h=n*p,r=n/s)),a=$t(h||e),o=$t(n||s),c=t("<canvas>")[0],c.width=a,c.height=o,d=c.getContext("2d"),i.fillColor&&(d.fillStyle=i.fillColor,d.fillRect(0,0,a,o)),d.drawImage.apply(d,function(){var t,i,a,o,h,n,p=l(this.$clone[0],this.image),c=p.width,d=p.height,u=this.canvas,f=[p],m=g.x+u.naturalWidth*(Ct(g.scaleX||1)-1)/2,v=g.y+u.naturalHeight*(Ct(g.scaleY||1)-1)/2;return-e>=m||m>c?m=t=a=h=0:0>=m?(a=-m,m=0,t=h=wt(c,e+m)):c>=m&&(a=0,t=h=wt(e,c-m)),0>=t||-s>=v||v>d?v=i=o=n=0:0>=v?(o=-v,v=0,i=n=wt(d,s+v)):d>=v&&(o=0,i=n=wt(s,d-v)),f.push($t(m),$t(v),$t(t),$t(i)),r&&(a*=r,o*=r,h*=r,n*=r),h>0&&n>0&&f.push($t(a),$t(o),$t(h),$t(n)),f}.call(this)),c):l(this.$clone[0],this.image):void 0},setAspectRatio:function(t){var i=this.options;this.isDisabled||e(t)||(i.aspectRatio=xt(0,t)||NaN,this.isBuilt&&(this.initCropBox(),this.isCropped&&this.renderCropBox()))},setDragMode:function(t){var i,e,s=this.options;this.isLoaded&&!this.isDisabled&&(i=t===lt,e=s.movable&&t===dt,t=i||e?t:ut,this.$dragBox.data(it,t).toggleClass(W,i).toggleClass(M,e),s.cropBoxMovable||this.$face.data(it,t).toggleClass(W,i).toggleClass(M,e))}},v.DEFAULTS={viewMode:0,dragMode:"crop",aspectRatio:NaN,data:null,preview:"",responsive:!0,restore:!0,checkCrossOrigin:!0,checkOrientation:!0,modal:!0,guides:!0,center:!0,highlight:!0,background:!0,autoCrop:!0,autoCropArea:.8,movable:!0,rotatable:!0,scalable:!0,zoomable:!0,zoomOnTouch:!0,zoomOnWheel:!0,wheelZoomRatio:.1,cropBoxMovable:!0,cropBoxResizable:!0,toggleDragModeOnDblclick:!0,minCanvasWidth:0,minCanvasHeight:0,minCropBoxWidth:0,minCropBoxHeight:0,minContainerWidth:200,minContainerHeight:100,build:null,built:null,cropstart:null,cropmove:null,cropend:null,crop:null,zoom:null},v.setDefaults=function(i){t.extend(v.DEFAULTS,i)},v.TEMPLATE='<div class="cropper-container"><div class="cropper-wrap-box"><div class="cropper-canvas"></div></div><div class="cropper-drag-box"></div><div class="cropper-crop-box"><span class="cropper-view-box"></span><span class="cropper-dashed dashed-h"></span><span class="cropper-dashed dashed-v"></span><span class="cropper-center"></span><span class="cropper-face"></span><span class="cropper-line line-e" data-action="e"></span><span class="cropper-line line-n" data-action="n"></span><span class="cropper-line line-w" data-action="w"></span><span class="cropper-line line-s" data-action="s"></span><span class="cropper-point point-e" data-action="e"></span><span class="cropper-point point-n" data-action="n"></span><span class="cropper-point point-w" data-action="w"></span><span class="cropper-point point-s" data-action="s"></span><span class="cropper-point point-ne" data-action="ne"></span><span class="cropper-point point-nw" data-action="nw"></span><span class="cropper-point point-sw" data-action="sw"></span><span class="cropper-point point-se" data-action="se"></span></div></div>',v.other=t.fn.cropper,t.fn.cropper=function(i){var a,o=s(arguments,1);return this.each(function(){var e,s,h=t(this),n=h.data(L);if(!n){if(/destroy/.test(i))return;e=t.extend({},h.data(),t.isPlainObject(i)&&i),h.data(L,n=new v(this,e))}"string"==typeof i&&t.isFunction(s=n[i])&&(a=s.apply(n,o))}),e(a)?this:a},t.fn.cropper.Constructor=v,t.fn.cropper.setDefaults=v.setDefaults,t.fn.cropper.noConflict=function(){return t.fn.cropper=v.other,this}});
;
(function() {
	'use strict';
	angular.module('bbs.configurations', ['app.configurations.config']).config(function(globalConfig) {
		globalConfig.moduleCode = 'bbs';
	})
}).call(this);
;
// 
// Here is how to define your module 
// has dependent on mobile-angular-ui
// 
var app = angular.module('bbs', [
    'ngRoute',
    'ngCookies',
    'ngAnimate',
    'mobile-angular-ui',
    'mobile-angular-ui.gestures',
    'angular-loading-bar',

    'app.configurations.config',
    'app.configurations.config-local',
    'app.constants.function-param',
    'app.constants.function-config',
    'app.directives.ui',
    'app.services.resource',
    'app.services.function',
    'app.controllers.auth',

    'bbs.configurations',
    'bbs.controllers',
    'bbs.resource'

]);

app.run(['$transform',function($transform) {
    window.$transform = $transform;
}]);

// 
// You can configure ngRoute as always, but to take advantage of SharedState location
// feature (i.e. close sidebar on backbutton) you should setup 'reloadOnSearch: false' 
// in order to avoid unwanted routing.
// 
app.config(['$routeProvider',function($routeProvider) {
    $routeProvider
        .when('/index',{
            templateUrl: 'views/bbs_index.html',
            controller:'bbsIndexController'
        })
        .when('/personal-center',{
            templateUrl: 'views/personal_center.html',
            controller:'personalCenterController'
        })
        .when('/score-detail',{
            templateUrl: 'views/score_detail.html',
            controller:'scoreDetailController'
        })
        .when('/circle',{
            templateUrl: 'views/circle.html',
            controller:'circleController'
        })
        .when('/circle-index',{
            templateUrl: 'views/circle_index.html',
            controller:'circleIndexController'
        })
        .when('/circle-rejoin',{
            templateUrl: 'views/circle_rejoin.html',
            controller:'circleRejoinController'
        })
        .when('/article-detail/:id',{
            templateUrl: 'views/article_detail.html',
            controller:'articleDetailController'
        })
        .when('/my-post',{
            templateUrl: 'views/my_post.html',
            controller:'myPostController'
        })
        .when('/my-reply',{
            templateUrl: 'views/my_reply.html',
            controller:'myReplyController'
        })
        .when('/my-collect',{
            templateUrl: 'views/my_collect.html',
            controller:'myCollectController'
        })
        .when('/my-circle',{
            templateUrl: 'views/my_circle.html',
            controller:'myCircleController'
        })
        .when('/post',{
            templateUrl: 'views/post.html',
            controller:'postController'
        })        
        .when('/search-result/:keyword',{
            templateUrl: 'views/search_result.html',
            controller:'searchController'
        })
        .when('/post-edit/:postId',{
            templateUrl: 'views/post_edit.html',
            controller:'postEditController'
        })
        .when('/auth',{
            templateUrl: '../../views/auth.html',
            controller:'authController'
        })
        .when('/qy_auth',{
            templateUrl: '../../views/auth.html',
            controller:'qyauthController'
        })
        .otherwise({
            redirectTo: '/index'
        });
}]);

app.config(function(weChatConfig){
    weChatConfig.jsApiList = ['onMenuShareTimeline','onMenuShareAppMessage']
});

app.config( [ '$compileProvider',function( $compileProvider ){
        $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|tel|file|sms):/);
        $compileProvider.imgSrcSanitizationWhitelist(/^\s*(http|wxlocalresource|weixin|data):/);
    }
]);

app.config(['$httpProvider', function($httpProvider) {
    $httpProvider.interceptors.push('errorInterceptor');
    $httpProvider.defaults.withCredentials = true;
}]);
;
(function() {
    'use strict';
    angular.module('bbs.controllers', [])
        .controller('mainController', ['$scope', '$location', '$route', '$window', 'userManager', 'user', function($scope, $location, $route, $window, userManager, user) {
            $scope.showHeader = false;
            $scope.user = user;
            $scope.currentPath = '';
            $scope.$on('$routeChangeSuccess', function(e) {
                $scope.currentPath = $location.path();
            })
            $scope.setHighlight = function(paths) {
                var pathArr = paths.split(',');
                var currentPath = $location.path();
                var isHighlight;
                pathArr.forEach(function(val, index, arr) {
                    if (currentPath.indexOf(arr[index]) != -1) {
                        isHighlight = true;
                    }
                });
                return isHighlight;
            };
            $window.document.title = '论坛';
        }])
        .controller('bbsIndexController', ['$scope', '$timeout', '$location', '$window', 'globalPagination', 'globalFunction', 'modalExtension', 'articleApi',
            function($scope, $timeout, $location, $window, globalPagination, globalFunction, modalExtension, articleApi) {

                // article list
                $timeout(function() {
                    $scope.$parent.showHeader = true;
                }, 0);
                //初始化列表數據
                $scope.pagination = globalPagination.create();
                $scope.pagination.resource = articleApi;
                $scope.pagination.sort = 'is_top,sort DESC';
                $scope.select = function(page) {
                    $scope.pagination.select(page, {}, { coteries: {}, user: {} }).$promise.then(function(data) {
                        $scope.bbsArticleList = _.union($scope.bbsArticleList, data);
                    });
                };
                $scope.search = function() {
                    $scope.bbsArticleList = [];
                    $scope.select(1);
                }

                $scope.setStatus = function(status) {
                    $scope.condition.status = status;
                    $scope.search();
                }

                $scope.bottomReached = globalFunction.debounce(function() {
                    if (!$scope.pagination.isLast()) {
                        $scope.select($scope.pagination.page + 1)
                    } else {
                        modalExtension.tips('没有更多内容');
                    }
                }, 20);
                $scope.search();

                // search
                $scope.isShow = false;
                $scope.init = function() {
                    $scope.isShow = false;
                    $('#searchFiled').val($scope.defaultKeyword);
                };
                $scope.searchFn = function(keyword) {
                    if (!keyword) {
                        modalExtension.tips('请输入搜索的内容');
                        $('#searchFiled').focus();
                    } else {
                        $location.path('/search-result/' + keyword);
                    }
                };

                $window.document.title = '论坛';
            }
        ])
        .controller('personalCenterController', ['$scope', '$window', 'personalCenterApi', 'scoreTotalApi', function($scope, $window, personalCenterApi, scoreTotalApi) {
            personalCenterApi.get().$promise.then(function(data) {
                $scope.userData = data;
            });

            // score total
            scoreTotalApi.get().$promise.then(function(data) {
                $scope.scoreTotal = data;
                console.log(data);
            });

            $window.document.title = '个人中心';
        }])
        .controller('scoreDetailController', ['$scope', '$window', 'personalCenterApi', 'scoreTotalApi', 'scoreListApi', function($scope, $window, personalCenterApi, scoreTotalApi, scoreListApi) {
            personalCenterApi.get().$promise.then(function(data) {
                $scope.userData = data;
            });
            // score rule
            $scope.showPopup = false;
            $scope.showRule = function() {
                $scope.showPopup = true;
            };

            // score total
            scoreTotalApi.get().$promise.then(function(data) {
                $scope.scoreTotal = data;
                console.log(data);
            });

            // score list
            scoreListApi.query().$promise.then(function(data) {
                $scope.scoreList = data;
            });

            $window.document.title = '积分详情';
        }])
        .controller('circleController', ['$scope', '$location', '$window', 'globalFunction', 'modalExtension', 'myCircleApi', 'notJoinedCircleApi', 'userApi',
            function($scope, $location, $window, globalFunction, modalExtension, myCircleApi, notJoinedCircleApi, userApi) {
                // circle category
                myCircleApi.query().$promise.then(function(data) {
                    var isJoined = false;
                    if (data.length > 0) {
                        isJoined = true;
                    }
                    /*angular.forEach($scope.categoryList, function(item) {
                        item.status = false;
                        if (item.user) {
                           isJoined = true; 
                        }
                    });*/


                    if (isJoined) {
                        $location.path('/circle-index');
                    } else {
                        $location.path('/circle');
                    }
                });
                notJoinedCircleApi.query().$promise.then(function(data) {
                    $scope.categoryList = data;

                    angular.forEach($scope.categoryList, function(item) {
                        item.status = false;
                    });
                });

                // join circle
                $scope.join = function(category) {
                    var params = {
                        coterie_id: category.id
                    }
                    userApi.save(params).$promise.then(function() {
                        modalExtension.tips('圈子加入成功');
                        angular.forEach($scope.categoryList, function(item) {
                            if (category.id == item.id) {
                                category.status = true;
                            }
                        });
                    });
                };

                $window.document.title = '圈子首页';
            }
        ])
        .controller('circleIndexController', ['$scope', '$timeout', '$location', '$window', 'globalFunction', 'globalPagination', 'modalExtension', 'myCircleApi', 'circleArticleApi',
            function($scope, $timeout, $location, $window, globalFunction, globalPagination, modalExtension, myCircleApi, circleArticleApi) {

                // search
                $scope.isShow = false;
                $scope.init = function() {
                    $scope.isShow = false;
                    $('#searchFiled').val($scope.defaultKeyword);
                };
                $scope.searchFn = function(keyword) {
                    if (!keyword) {
                        modalExtension.tips('请输入搜索的内容');
                        $('#searchFiled').focus();
                    } else {
                        $location.path('/search-result/' + keyword);
                    }
                };

                // joined circle
                myCircleApi.query().$promise.then(function(data) {
                    $scope.circlesJoined = data;
                });

                // article list
                $timeout(function() {
                    $scope.$parent.showHeader = true;
                }, 0);
                $scope.condition = {};
                //初始化列表數據
                $scope.pagination = globalPagination.create();
                $scope.pagination.resource = circleArticleApi;
                $scope.select = function(page) {
                    $scope.pagination.select(page, $scope.condition, { coteries: { user: '' }, user: {} }).$promise.then(function(data) {
                        $scope.circleArticleList = _.union($scope.circleArticleList, data);
                    });
                };
                $scope.search = function() {
                    $scope.circleArticleList = [];
                    $scope.select(1);
                }

                $scope.setStatus = function(status) {
                    $scope.condition.status = status;
                    $scope.search();
                }

                $scope.bottomReached = globalFunction.debounce(function() {
                    if (!$scope.pagination.isLast()) {
                        $scope.select($scope.pagination.page + 1)
                    } else {
                        modalExtension.tips('没有更多内容');
                    }
                }, 20);
                $scope.search();

                // article filter
                $scope.setFilter = function(circleId, index) {
                    $scope.circleIndex = index;
                    $scope.condition['coterie_id'] = circleId;
                    $scope.search();
                };

                // circle rejoin
                $scope.rejoin = function() {
                    $location.path('/circle-rejoin');
                };

                $window.document.title = '圈子首页';
            }
        ])
        .controller('circleRejoinController', ['$scope', '$route', '$location', '$window', 'globalFunction', 'modalExtension', 'myCircleApi', 'notJoinedCircleApi', 'userApi',
            function($scope, $route, $location, $window, globalFunction, modalExtension, myCircleApi, notJoinedCircleApi, userApi) {
                // joined circle
                myCircleApi.query(globalFunction.generateUrlParams({}, { myCoterie: {} })).$promise.then(function(data) {
                    $scope.joinedList = data;
                    angular.forEach($scope.joinedList, function(item) {
                        item.status = true;
                    });
                });

                // not joined circle
                notJoinedCircleApi.query().$promise.then(function(data) {
                    $scope.notJoinedList = data;
                    angular.forEach($scope.notJoinedList, function(item) {
                        item.status = false;
                    });
                });
                // join circle
                $scope.join = function(category) {
                    var params = {
                        coterie_id: category.id
                    };
                    userApi.save(params).$promise.then(function() {
                        modalExtension.tips('圈子加入成功');
                        angular.forEach($scope.notJoinedList, function(item) {
                            if (category.id == item.id) {
                                category.status = true;
                            }
                        });
                        $route.reload();
                    });
                };

                // exit circle
                $scope.exit = function(category) {
                    var params = {
                        coterie_id: category.id
                    };
                    userApi.delete(category.myCoterie).$promise.then(function() {
                        modalExtension.tips('圈子退出成功');
                        angular.forEach($scope.joinedList, function(item) {
                            if (category.id == item.id) {
                                category.status = false;
                            }
                        });
                        $route.reload();
                    });
                };
            }
        ])
        .controller('articleDetailController', ['$scope', '$timeout', '$location', '$window', '$routeParams', 'globalFunction', 'globalPagination', 'modalExtension', 'articleApi', 'commentReplyApi', 'commentAddApi', 'flowApi', 'commentFlowApi', 'weChat',
            function($scope, $timeout, $location, $window, $routeParams, globalFunction, globalPagination, modalExtension, articleApi, commentReplyApi, commentAddApi, flowApi, commentFlowApi, weChat){
                // article info
                articleApi.get(globalFunction.generateUrlParams({ id: $routeParams.id }, { coteries: {}, user: {} })).$promise.then(function(data) {
                    $scope.articleData = data;
                    weChat.onMenuShareTimeline({
                        title: data.title, // 分享标题
                        link: '', // 分享链接
                        desc: data.description, // 分享描述
                        imgUrl:data.user.avatar , // 分享图标
                    });
                    weChat.onMenuShareAppMessage({
                        title: data.title, // 分享标题
                        desc: data.description, // 分享描述
                        link: '', // 分享链接
                        imgUrl:data.user.avatar, // 分享图标
                        type: '', // 分享类型,music、video或link，不填默认为link
                        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                        success: function() {
                            // 用户确认分享后执行的回调函数
                        },
                        cancel: function() {
                            // 用户取消分享后执行的回调函数
                        }
                    });
                });

                // article actions
                $scope.articleCollectFn = function(articleId) {
                    var params = {
                        id: articleId,
                        type: 1
                    };
                    flowApi.update(params).$promise.then(function(data) {
                        if (data.status == 1) { // status => 1(收藏成功) 2(已收藏)
                            modalExtension.tips('收藏成功');
                            $scope.articleData.collect_count++;
                        } else if (data.status == 2) {
                            modalExtension.tips('已收藏');
                        }
                    });
                };

                $scope.articleLikedFn = function(articleId) {
                    var params = {
                        id: articleId,
                        type: 2
                    };
                    flowApi.update(params).$promise.then(function(data) {
                        if (data.status == 1) { // status => 1(点赞成功) 2(已点赞)
                            modalExtension.tips('点赞成功,奖励1积分');
                            $scope.articleData.admire_count++;
                        } else if (data.status == 2) {
                            modalExtension.tips('已点赞');
                        }
                    });
                };

                // comment list
                $timeout(function() {
                    $scope.$parent.showHeader = true;
                }, 0);
                $scope.condition = {
                    'article_id': $routeParams.id
                };
                //初始化列表數據
                $scope.pagination = globalPagination.create();
                $scope.pagination.resource = commentReplyApi;
                $scope.select = function(page) {
                    $scope.pagination.select(page, $scope.condition, { flow: {} }).$promise.then(function(data) {
                        $scope.commentList = _.union($scope.commentList, data);
                    });
                };
                $scope.search = function() {
                    $scope.commentList = [];
                    $scope.select(1);
                }

                $scope.setStatus = function(status) {
                    $scope.condition.status = status;
                    $scope.search();
                }
                $scope.bottomReached = globalFunction.debounce(function() {
                    if (!$scope.pagination.isLast()) {
                        $scope.select($scope.pagination.page + 1)
                    } else {
                        modalExtension.tips('没有更多内容');
                    }
                }, 20);
                $scope.search();

                // comment like
                $scope.commentLikedFn = function(commentId) {
                    var params = {
                        id: commentId,
                        type: 3
                    };
                    commentFlowApi.save(params).$promise.then(function(data) {
                        console.log(data);
                        if (data.status == 1) { // status => 1(点赞成功) 2(已点赞)
                            modalExtension.tips('点赞成功,奖励1积分');
                            angular.forEach($scope.commentList, function(item) {
                                if (item.id == params.id) {
                                    item.admire_count++;
                                    item.flow = { type: 3 };
                                }
                            });
                        } else if (data.status == 2) {
                            modalExtension.tips('已点赞');
                        }
                    });
                };

                // add comment
                $scope.commentSubmitFn = function(inputVal) {
                    if (inputVal == undefined || inputVal == '') {
                        modalExtension.alert('请添加评论内容').then(function() {
                            $('#commentField').focus();
                        })
                    } else {
                        var params = {
                            article_id: $routeParams.id,
                            comment: inputVal
                        }
                        commentAddApi.save(params).$promise.then(function(data) {
                            modalExtension.tips('评论成功,奖励1积分');
                            $scope.commentContent = '';
                            $scope.search();
                            /*modalExtension.alert('评论成功,奖励1积分', '查看其他帖子').then(function(){
                                $scope.search();
                                $scope.commentContent = '';
                                $location.path('/index');
                            });*/
                        });
                    }
                };

                $window.document.title = '文章详情';
            }
        ])
        .controller('myPostController', ['$scope', '$location', '$window', '$timeout', 'globalFunction', 'globalPagination', 'modalExtension', 'myPostApi', 'postDelApi',
            function($scope, $location, $window, $timeout, globalFunction, globalPagination, modalExtension, myPostApi, postDelApi) {
                // post list
                $timeout(function() {
                    $scope.$parent.showHeader = true;
                }, 0);
                //初始化列表數據
                $scope.pagination = globalPagination.create();
                $scope.pagination.resource = myPostApi;
                $scope.select = function(page) {
                    $scope.pagination.select(page).$promise.then(function(data) {
                        $scope.postList = _.union($scope.postList, data);
                    });
                };
                $scope.search = function() {
                    $scope.postList = [];
                    $scope.select(1);
                }

                $scope.setStatus = function(status) {
                    $scope.condition.status = status;
                    $scope.search();
                }

                $scope.bottomReached = globalFunction.debounce(function() {
                    if (!$scope.pagination.isLast()) {
                        $scope.select($scope.pagination.page + 1)
                    } else {
                        modalExtension.tips('没有更多内容');
                    }
                }, 20);
                $scope.search();

                // operation
                $scope.postDel = function(post) {
                    var params = {
                        channel: 'bbs',
                        id: post.id,
                        do: 'del'
                    };
                    postDelApi.save(params).$promise.then(function() {
                        modalExtension.tips('帖子删除成功');
                        $scope.search();
                    });
                };

                $window.document.title = '我的发帖';
            }
        ])
        .controller('myReplyController', ['$scope', '$location', '$window', '$timeout', 'globalFunction', 'globalPagination', 'modalExtension', 'replyApi',
            function($scope, $location, $window, $timeout, globalFunction, globalPagination, modalExtension, replyApi) {
                // reply list
                $timeout(function() {
                    $scope.$parent.showHeader = true;
                }, 0);
                //初始化列表數據
                $scope.pagination = globalPagination.create();
                $scope.pagination.resource = replyApi;
                $scope.select = function(page) {
                    $scope.pagination.select(page, {}, { article: {} }).$promise.then(function(data) {
                        $scope.replyList = _.union($scope.replyList, data);
                    });
                };
                $scope.search = function() {
                    $scope.replyList = [];
                    $scope.select(1);
                }

                $scope.setStatus = function(status) {
                    $scope.condition.status = status;
                    $scope.search();
                }

                $scope.bottomReached = globalFunction.debounce(function() {
                    if (!$scope.pagination.isLast()) {
                        $scope.select($scope.pagination.page + 1)
                    } else {
                        modalExtension.tips('没有更多内容');
                    }
                }, 20);
                $scope.search();

                $window.document.title = '我的回帖';
            }
        ])
        .controller('myCollectController', ['$scope', '$location', '$window', '$timeout', 'globalFunction', 'globalPagination', 'modalExtension', 'collectApi',
            function($scope, $location, $window, $timeout, globalFunction, globalPagination, modalExtension, collectApi) {
                // collect list
                $timeout(function() {
                    $scope.$parent.showHeader = true;
                }, 0);
                $scope.condition = {
                    type: 1
                };
                //初始化列表數據
                $scope.pagination = globalPagination.create();
                $scope.pagination.resource = collectApi;
                $scope.select = function(page) {
                    $scope.pagination.select(page, $scope.condition, { article: {} }).$promise.then(function(data) {
                        $scope.collectList = _.union($scope.collectList, data);
                    });
                };
                $scope.search = function() {
                    $scope.collectList = [];
                    $scope.select(1);
                }

                $scope.setStatus = function(status) {
                    $scope.condition.status = status;
                    $scope.search();
                }

                $scope.bottomReached = globalFunction.debounce(function() {
                    if (!$scope.pagination.isLast()) {
                        $scope.select($scope.pagination.page + 1)
                    } else {
                        modalExtension.tips('没有更多内容');
                    }
                }, 20);
                $scope.search();

                $window.document.title = '我的收藏';
            }
        ])
        .controller('myCircleController', ['$scope', '$route', '$window', 'globalFunction', 'modalExtension', 'myCircleApi', 'userApi', function($scope, $route, $window, globalFunction, modalExtension, myCircleApi, userApi) {
            // joined circle
            myCircleApi.query(globalFunction.generateUrlParams({}, { myCoterie: {} })).$promise.then(function(data) {
                $scope.joinedList = data;
                angular.forEach($scope.joinedList, function(item) {
                    item.status = true;
                });
            });

            // exit circle
            $scope.exit = function(category) {
                var params = {
                    coterie_id: category.id
                };
                userApi.delete(category.myCoterie).$promise.then(function() {
                    modalExtension.tips('圈子退出成功');
                    angular.forEach($scope.joinedList, function(item) {
                        if (category.id == item.id) {
                            category.status = false;
                        }
                    });
                    $route.reload();
                });
            };
            $window.document.title = '我加入的圈子';
        }])
        .controller('postController', ['$scope', '$location', '$window', 'globalFunction', 'modalExtension', 'categaryApi', 'articleApi',
            function($scope, $location, $window, globalFunction, modalExtension, categaryApi, articleApi) {
                // pic crop
                $scope.hasCropImg = false;

                // circle list
                categaryApi.query(globalFunction.generateUrlParams({ sort: 'sort DESC' })).$promise.then(function(data) {
                    $scope.circleList = data;
                    angular.forEach($scope.circleList, function(item) {
                        item.isCurrent = false;
                    });
                });
                $scope.hasSelectCircle = false;
                $scope.selectCircleFn = function(circleId) {
                    angular.forEach($scope.circleList, function(item) {
                        item.isCurrent = false;
                        if (item.id == circleId) {
                            item.isCurrent = true;
                            $scope.hasSelectCircle = true;
                        }
                    });
                }

                // form validate
                $scope.save = function() {
                    if ($scope.postForm.postTitle.$invalid) {
                        $scope.showErrorTitle = true;
                    } else {
                        $scope.showErrorTitle = false;
                        if ($('#target').val() == '') {
                            $scope.showErrorContent = true;
                        } else {
                            $scope.showErrorContent = false;
                            if (!$scope.hasSelectCircle) {
                                $scope.showErrorSelectCircle = true;
                            } else {
                                $scope.showErrorSelectCircle = false;
                            }
                        }
                    }

                    if ($scope.postForm.$valid && $scope.hasSelectCircle) {
                        var postThumb = $('.crop-img img').attr('src'),
                            postCircleId = $('.click-on').attr('data-circle-id'),
                            postContent = $('#target').val();
                        var postObj = {
                            image: postThumb || '',
                            coterie_id: postCircleId,
                            title: $scope.postTitle,
                            content: postContent,
                            is_anonymity: $scope.postAnonymous || 0
                        };
                        console.log(postObj);
                        articleApi.save(postObj).$promise.then(function(data) {
                            modalExtension.alert('发帖成功,奖励5积分', '查看发表的帖子').then(function() {
                                $location.path('/article-detail/' + 　data.id);
                            })
                        });
                    }

                };
                $window.document.title = '我要发帖';
            }
        ])
        .controller('postEditController', ['$scope', '$location', '$window', '$routeParams', 'globalFunction', 'modalExtension', 'categaryApi', 'articleApi',
            function($scope, $location, $window, $routeParams, globalFunction, modalExtension, categaryApi, articleApi) {
                // circle list
                categaryApi.query(globalFunction.generateUrlParams({ sort: 'sort DESC' })).$promise.then(function(data) {
                    $scope.circleList = data;
                    angular.forEach($scope.circleList, function(item) {
                        item.isCurrent = false;
                    });
                });

                // post info
                articleApi.get(globalFunction.generateUrlParams({ id: $routeParams.postId }, { coteries: {}, user: {} })).$promise.then(function(data) {
                    $scope.postTitle = data.title;
                    $('#content').html(data.content);
                    angular.forEach($scope.circleList, function(item) {
                        if (item.id == data.coteries.coterie_id) {
                            item.isCurrent = true;
                        }
                    });
                    $scope.isAnonymous = data.is_anonymity == 1 ? true : false;
                    $scope.hasCropImg = data.image != '' ? true : false;
                    if (data.image != '') {
                        $scope.thumbPic = data.image;
                    }
                });
                $scope.hasSelectCircle = false;
                $scope.selectCircleFn = function(circleId) {
                    angular.forEach($scope.circleList, function(item) {
                        item.isCurrent = false;
                        if (item.id == circleId) {
                            item.isCurrent = true;
                            $scope.hasSelectCircle = true;
                        }
                    });
                };
                // form validate
                $scope.save = function() {
                    if ($scope.postForm.postTitle.$invalid) {
                        $scope.showErrorTitle = true;
                    } else {
                        $scope.showErrorTitle = false;
                        if ($('#target').val() == '') {
                            $scope.showErrorContent = true;
                        } else {
                            $scope.showErrorContent = false;
                            if (!$scope.hasSelectCircle) {
                                $scope.showErrorSelectCircle = true;
                            } else {
                                $scope.showErrorSelectCircle = false;
                            }
                        }
                    }

                    if ($scope.postForm.$valid && $scope.hasSelectCircle) {
                        var postThumb = $('.crop-img img').attr('src'),
                            postCircleId = $('.click-on').attr('data-circle-id'),
                            postContent = $('#target').val();
                        var postObj = {
                            image: postThumb || '',
                            coterie_id: postCircleId,
                            title: $scope.postTitle,
                            content: postContent,
                            is_anonymity: $scope.postAnonymous || 0
                        };
                        console.log(postObj);
                        articleApi.update({ id: $routeParams.postId }, postObj).$promise.then(function(data) {
                            modalExtension.alert('更新成功', '查看发表的帖子').then(function() {
                                $location.path('/article-detail/' + 　data.id);
                            })
                        });
                    }

                };

                $window.document.title = '编辑帖子';
            }
        ])
        .controller('searchController', ['$scope', '$window', '$routeParams', '$timeout', '$route', 'globalPagination', 'globalFunction', 'modalExtension', 'articleApi', 'conditionTypes',
            function($scope, $window, $routeParams, $timeout, $route, globalPagination, globalFunction, modalExtension, articleApi, conditionTypes) {

                // console.log($routeParams.keyword);

                // article list
                $timeout(function() {
                    $scope.$parent.showHeader = true;
                }, 0);
                $scope.article = {
                        name: $routeParams.keyword
                    }
                    //初始化列表數據
                $scope.pagination = globalPagination.create();
                $scope.pagination.resource = articleApi;
                $scope.pagination.sort = 'is_top,sort DESC';
                $scope.select = function(page) {
                    $scope.pagination.select(page, $scope.condition_copy, { coteries: {}, user: {} }).$promise.then(function(data) {
                        $scope.isDataNull = (data.length == 0) ? true : false;
                        $scope.articleList = _.union($scope.articleList, data);
                    });
                };
                $scope.search = function() {
                    $scope.condition = {
                        title: { type: conditionTypes.like, value: $scope.article.name }
                    };
                    $scope.condition_copy = angular.copy($scope.condition);
                    $scope.articleList = [];
                    $scope.select(1);
                }

                $scope.setStatus = function(status) {
                    $scope.condition.status = status;
                    $scope.search();
                }

                $scope.setFilter = function(typeId) {
                    $scope.condition.type_id = typeId;
                    $scope.search();
                }

                $scope.bottomReached = globalFunction.debounce(function() {
                    if (!$scope.pagination.isLast()) {
                        $scope.select($scope.pagination.page + 1)
                    } else {
                        modalExtension.tips('没有更多内容');
                    }
                }, 20);
                $scope.search();

                $window.document.title = '搜索结果';
            }
        ])
        .directive('errSrc', function() {
            return {
                restrict: 'A',
                link: function(scope, element, attr) {
                    element.bind('error', function() {
                        if (attr.src != attr.errSrc) {
                            attr.$set('src', attr.errSrc);
                        }
                    });
                }
            };
        })
        .directive('picPreview', function($compile) {
            return {
                restrict: 'A',
                link: function(scope, element, attr) {
                    element.find('input').eq(0).on('change', function(event) {
                        if (event.target.files && event.target.files[0]) {
                            element.children().eq(0).css('display', 'none');
                            element.children().eq(1).css('display', 'block');
                            var reader = new FileReader();

                            reader.onload = function(e) {
                                element.children().eq(1).append($compile('<div cropper><img src="' + e.target.result + '" width="100%"></div>')(scope));
                            };

                            reader.readAsDataURL(event.target.files[0]);
                            $('#btnConfirm').show();
                        }
                    });
                }
            }
        })
        .directive('picUpdatePreview', function($compile) {
            return {
                restrict: 'A',
                link: function(scope, element, attr) {
                    element.on('change', function(event) {
                        if (event.target.files && event.target.files[0]) {
                            $('.post').hide().next().show();
                            var reader = new FileReader();

                            reader.onload = function(e) {
                                $('#preview').empty().append($compile('<div cropper><img src="' + e.target.result + '" width="100%"></div>')(scope));
                            };

                            reader.readAsDataURL(event.target.files[0]);
                            $('#btnConfirm').show();
                        }
                    });
                }
            }
        })
        .directive('cropper', function() {
            return {
                restrict: 'A',
                link: function(scope, element, attr) {
                    var convertToData = function(url, canvasdata, cropdata, callback) {
                        var cropw = cropdata.width; // 剪切的宽
                        var croph = cropdata.height; // 剪切的高
                        var imgw = canvasdata.width; // 图片缩放或则放大后的宽
                        var imgh = canvasdata.height; // 图片缩放或则放大后的高
                        var poleft = canvasdata.left - cropdata.left; // canvas定位图片的左边位置
                        var potop = canvasdata.top - cropdata.top; // canvas定位图片的上边位置
                        var canvas = document.createElement("canvas");
                        var ctx = canvas.getContext('2d');
                        canvas.width = cropw;
                        canvas.height = croph;
                        var img = new Image();
                        img.src = url;
                        img.onload = function() {
                            this.width = imgw;
                            this.height = imgh;
                            // 这里主要是懂得canvas与图片的裁剪之间的关系位置
                            ctx.drawImage(this, poleft, potop, this.width, this.height);
                            var base64 = canvas.toDataURL('image/jpg', 1); // 这里的“1”是指的是处理图片的清晰度（0-1）之间，当然越小图片越模糊，处理后的图片大小也就越小
                            callback && callback(base64) // 回调base64字符串
                        }

                    };
                    var cropBox = $(element)
                    var image = cropBox.find('img'),
                        btnConfirm = $('#btnConfirm');
                    image.on('load', function() {
                        image.cropper({
                            dragMode: 'none',
                            aspectRatio: NaN,
                            autoCropArea: 1,
                            scalable: true,
                            zoomable: false,
                            cropBoxResizable: true,
                            movable: false
                        });
                    });

                    btnConfirm.on('click', function() {
                        var src = image.eq(0).attr('src');
                        var canvasdata = image.cropper('getCanvasData');
                        var cropBoxData = image.cropper('getCropBoxData');

                        convertToData(src, canvasdata, cropBoxData, function(basechar) {
                            // 回调后的函数处理 
                            // console.log(basechar);
                            scope.$apply(function() {
                                scope.hasCropImg = true;
                            });
                            $('.crop-img > img').attr('src', basechar);
                            $('#preview').hide().prev().show();
                            $('#btnConfirm').hide();
                        });
                    });
                }
            }
        })
        .directive('artEditor', function(globalFunction) {
            return {
                restrict: 'A',
                link: function(scope, element, attr) {
                    var token = window.sessionStorage.getItem('token');
                    $(element).artEditor({
                        imgTar: '#imageUpload',
                        limitSize: 5, // 兆
                        showServer: true,
                        uploadUrl: globalFunction.getApiUrl('bbs/article/upload?PHPSESSID=' + token),
                        data: {},
                        uploadField: 'image',
                        placeholader: '<p>请输入文章正文内容</p>',
                        validHtml: ["<br/>"],
                        formInputId: 'target',
                        uploadSuccess: function(res) {
                            // return img url
                            return res.image;
                        },
                        uploadError: function(res) {
                            // something error
                            console.log(res);
                        }
                    });
                }
            }
        })
        .directive('backToTop', function() {
            return {
                restrict: 'A',
                require: '^scrollableContent',
                link: function(scope, element, attr, ctrl) {
                    var scrollableContentController = ctrl;
                    // scrollableContentController.scrollTo(200);
                    var ele = $(element);
                    $(window).on('touchmove', function() {
                        if ($('#myScrollableContent').scrollTop() > 400) {
                            ele.fadeIn(200);
                        } else {
                            ele.fadeOut(200);
                        }
                    });
                    ele.on('click', function() {
                        scrollableContentController.scrollTo(0);
                        ele.fadeOut(200);
                    });
                }
            }
        })
        /*.directive('contenteditable',function(){
            return {
                restrict:'A',
                require:'?ngModel',
                link:function(scope,element,atrrs,ngModel){
                    debugger;
                    if(!ngModel)return;
                    ngModel.$render=function(){
                        element.html(ngModel.$viewValue||'');
                    }
                    element.on('blur keyup change', function() {
                        scope.$apply(read);
                    });
                    read();
                    function read() {
                        var html = element.html();
                        ngModel.$setViewValue(html);
                    }
                }
            }
        })*/
        .directive('errSrc', function() {
            return {
                restrict: 'A',
                link: function(scope, element, attr) {
                    element.bind('error', function() {
                        if (attr.src != attr.errSrc) {
                            attr.$set('src', attr.errSrc);
                        }
                    });
                }
            };
        })
        .filter('trust', ['$sce', function($sce) {
            return function(val, str) {
                switch (str) {
                    case 'html':
                        return $sce.trustAsHtml(val);
                    case 'js':
                        return $sce.trustAsJs(val);
                    case 'css':
                        return $sce.trustAsCss(val);
                    case 'url':
                        return $sce.trustAsUrl(val);
                    case 'resourceUrl':
                        return $sce.trustAsResourceUrl(val);
                    default:
                        return '未可知';
                }
            };

        }]);
}).call(this);

;
(function() {
'use strict';
angular.module('bbs.resource', ['ngResource'])
	.factory('userApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/user');
	}])
	.factory('articleApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/article');
	}])
	.factory('categaryApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/coterie');
	}])
	.factory('personalCenterApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/user/detail');
	}])
	.factory('commentApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/comment');
	}])
	.factory('commentAddApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/comment');
	}])
	.factory('commentReplyApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/comment/and-reply');
	}])
	.factory('flowApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/article/flow');
	}])
	.factory('commentFlowApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/comment/flow');
	}])
	.factory('collectApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/flow');
	}])
	.factory('replyApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/comment/comment-article');
	}])
	.factory('myPostApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/article/my');
	}])
	.factory('myCircleApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/coterie/my');
	}])
	.factory('circleArticleApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/article/coteries');
	}])
	.factory('notJoinedCircleApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('bbs/coterie/no');
	}])
	.factory('postDelApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('user/operate/publish');
	}])
	.factory('scoreTotalApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('common/score/stat');
	}])
	.factory('scoreListApi', ['globalFunction', function(globalFunction){
		return globalFunction.createResource('common/score');
	}])
}).call(this);
;
(function() {
    'use strict';
    angular.module('app.constants.function-param', [])
        .constant('conditionTypes',{
            "equal":'EQUAL',
            "notEqual":'NOTEQUAL',
            "null":'NULL',
            "like":'LIKE',
            "leftLike":'LLIKE',
            "rightLike":'RLIKE',
            "in":'IN',
            "notIn":'NOTIN',
            "min":'MIN',
            "max":'MAX'
        })
        .constant('genders',{
            "0":'',
            "1":'男',
            "2":'女'
        })
}).call(this);

;
(function() {
    'use strict';
    angular.module('app.constants.function-config', [])
        .constant('weChatConfig',{
            apiUrl:"/common/user/js-api-config",
            qy_apiUrl:"/common/qy-wx-user/js-api-config-qywx",
            jsApiList:[]
        })
        .constant('paginationConfig', {
            itemsPerPage: 15
        })
        .config(['cfpLoadingBarProvider', function(cfpLoadingBarProvider) {
            cfpLoadingBarProvider.includeBar = true;
            cfpLoadingBarProvider.includeSpinner = true;
            cfpLoadingBarProvider.spinnerTemplate = '<div class="modal"><div class="toast"><div class="loading-box" ><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></div><div class="obtain">加载中</div></div></div>'
        }])
}).call(this);

;
/**
 * Created by harry on 2015/3/26.
 */
(function() {
    'use strict';
    angular.module('app.services.resource',['ngResource'])
        .factory('commonUserApi',['globalFunction',function(globalFunction){
            return globalFunction.createResource('common/user',{},{
            	'qyauth':{method:'get',url:('common/qy-wx-user/login')},
                'auth':{method:'POST',url:('common/user/auth')},
                'login':{method:'GET',url:('common/user/login')}
            });
        }])
}).call(this);

;
(function() {
    'use strict';
    angular.module('app.services.function', ['ngCookies'])
        .service('globalFunction', ['globalConfig', '$resource', function(globalConfig, $resource) {
            this.getApiUrl = function(url) {
                return globalConfig.apiUrl + '/' + url;
            }
            this.generateUrlParams = function(condtion, fields) {
                var params = {};
                //set condition
                var setParams = function(params, obj, prefix) {
                    _.each(obj, function(value, key, list) {
                        if (_.isObject(value)) {
                            if (_.isEqual(_.keys(value), ['type', 'value'])) {
                                params[prefix + key] = value.type + '_' + value.value;
                            } else if (_.isEqual(_.keys(value), ['value'])) {
                                params[prefix + key] = value.value;
                            } else {
                                setParams(params, value, prefix + key + '.')
                            }
                        } else {
                            params[prefix + key] = value;
                        }

                    })
                    return obj;
                }
                setParams(params, condtion, '')

                //set fields
                if (fields) {
                    params['fields'] = [];
                    params['expand'] = [];
                    params['expand-fields'] = {};
                    _.each(fields, function(value, key, list) {
                        if (_.isObject(value)) {
                            //console.log(key);
                            params['expand'].push(key);
                            setParams(params['expand-fields'], value, key + '.');
                        } else {
                            params['fields'].push(key);
                        }
                    })
                    params['fields'] = params['fields'].join(',');
                    params['expand'] = params['expand'].join(',');
                    params['expand-fields'] = _.keys(params['expand-fields']).join(',');
                }
                return params;
            }
            this.createResource = function(url, param_defaults, actions) {
                var self = this;
                var inner_actions = {
                    'get': { method: 'GET', url: this.getApiUrl(url + '/:id') },
                    'query': { method: 'GET', isArray: true },
                    'update': { method: 'PUT', url: this.getApiUrl(url + '/:id') },
                    'delete': { method: 'DELETE', url: this.getApiUrl(url + '/:id') }
                };

                var inner_param_defaults = { id: "@id" };
                if (sessionStorage.token) {
                    inner_param_defaults.PHPSESSID = sessionStorage.token;
                }

                _.each(actions, function(action) { action.url = self.getApiUrl(action.url) })

                actions = _.extend(inner_actions, actions);
                param_defaults = _.extend(inner_param_defaults, param_defaults);
                return $resource(this.getApiUrl(url), param_defaults, actions);
            }
            this.debounce = function(fun, wait) {
                if (angular.isUndefined(wait))
                    wait = 800;
                return _.debounce(fun, wait);
            }
        }])
        .factory('globalPagination', ['paginationConfig', '$http', '$q', 'globalFunction', function(paginationConfig, $http, $q, globalFunction) {
            return {
                create: function(options) {
                    var pagination = {
                        items_per_page: paginationConfig.itemsPerPage,
                        total_items: 0,
                        total_pages: 0,
                        page: 1,
                        max_size: paginationConfig.maxSize,
                        query_method: 'query',
                        resource: null,
                        sort: "",
                        condition: {},
                        fields: {},
                        select: function(page, condition, fields) {
                            if (condition == null)
                                condition = this.condition;
                            if (fields == null)
                                fields = this.fields;
                            condition['page'] = this.page = page ? page : 1;
                            condition['per-page'] = this.items_per_page;
                            var _self = this;
                            if (this.sort)
                                condition.sort = this.sort;
                            else
                                delete condition.sort;
                            return this.resource[_self.query_method](globalFunction.generateUrlParams(condition, fields), function(data, headers) {
                                _self.total_items = headers('X-Pagination-Total-Count');
                                _self.total_pages = headers('X-Pagination-Page-Count');
                            });
                        },
                        isLast: function() {
                            return this.total_pages <= this.page;
                        }
                    }
                    return _.extend(pagination, options);
                }
            }

        }])
        .factory('validateInterceptor', ['$q', 'validateForms', 'topAlert', function($q, validateForms, topAlert) {
            return {
                'responseError': function(response) {
                    var form_name = response.config.method + response.config.url;
                    if (/[a-zA-Z0-9]{32}$/.test(form_name))
                        form_name = form_name.slice(0, -33);
                    if (response.status == 422 && validateForms.forms.hasOwnProperty(form_name)) {
                        var current_form = validateForms.forms[form_name];
                        _.each(current_form, function(field, key) {
                            var err_item;
                            if (key.substr(0, 1) != '$' && typeof(current_form[key]) != "function") {
                                current_form[key].$setValidity('server', true);
                                current_form[key].server_error = "";
                            }
                        })

                        var setErrors = function(error, prefix) {
                            _.each(error, function(value, key, list) {
                                if (/^\d+$/.test(key) && prefix == '')
                                    prefix = 'parent_'
                                if (_.isObject(value))
                                    setErrors(value, prefix + key + '_');
                                else {
                                    var current_key = prefix + key;
                                    if (!angular.isUndefined(current_form[current_key])) {
                                        current_form[current_key].$setValidity('server', false);
                                        current_form[current_key].server_error = value;
                                    } else {
                                        console.log(current_key);
                                        //TODO 返回来的错误在form中找不到对应的元素，应放在全局错误提示里
                                    }
                                    topAlert.warning(value);
                                }
                            })
                            return error;
                        }
                        setErrors(response.data, '');
                    }
                    return $q.reject(response);
                }
            }
        }])
        .factory('errorInterceptor', ['$q', '$rootScope', 'globalConfig', '$window', function($q, $rootScope, globalConfig, $window) {
            var showError = function(msg) {
                var error = $('<div class="modal"><div class="toast"><div class="obtain">' + msg + '</div></div></div>');
                $('body').append(error);
                setTimeout(function() {
                    error.remove();
                }, 2000)
            }
            return {
                'responseError': function(response) {
                    if (response.data.status == 400 || response.data.status == 403) {
                        showError(response.data.message);
                    }
                    return $q.reject(response);
                }
            }
        }])
        .service('validateForms', [function() {
            this.forms = [];
        }])
        .service('user', function() {
            this.id;
            this.checkAuth = function() {
                return this.id ? true : false;
            }
        })
        .service('userManager', ['commonUserApi', '$q', '$cookies', 'globalFunction', 'globalConfig', '$location', 'user',
            function(commonUserApi, $q, $cookies, globalFunction, globalConfig, $location, user) {
                var _self = this;
                this.restorageUserInfo = function(data) {
                    if (data)
                        _.extend(user, data);
                    else if (sessionStorage.user)
                        _.extend(user, JSON.parse(sessionStorage.user));
                    else if ($cookies.user) {
                        sessionStorage.setItem('user', $cookies.user);
                        sessionStorage.setItem("token", $cookies.user.token);
                        _.extend(user, $cookies.user);
                    }

                }
                this.auth = function(user_info) {
                    var deferred = $q.defer();
                    commonUserApi.auth(user_info).$promise.then(function(response) {
                        _self.restorageUserInfo(response);
                        sessionStorage.setItem('user', JSON.stringify(response));
                        $cookies.user = response;
                        sessionStorage.setItem("token", response.token);
                        deferred.resolve();
                    }, function(response) {
                        deferred.reject();
                    });
                    return deferred.promise;
                }
                this.gotoAuth = function(corp_id) {
                    var oauth_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=';
                    oauth_url += corp_id;
                    oauth_url += '&redirect_uri=';
                    oauth_url += encodeURIComponent($location.absUrl());
                    oauth_url += '&response_type=code&scope=snsapi_base&state=#wechat_redirect';
                    location.href = oauth_url;
                }
                this.gotoqyAuth = function(corp_id) {
                    var oauth_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=';
                    oauth_url += corp_id;
                    oauth_url += '&redirect_uri=';
                    oauth_url += encodeURIComponent($location.absUrl());
                    oauth_url += '&response_type=code&scope=SCOPE&state=STATE#wechat_redirect';
                    location.href = oauth_url;
                }
                this.qyauth = function(user_info) {
                    var deferred = $q.defer();
                    commonUserApi.qyauth(user_info).$promise.then(function(response) {
                        _self.restorageUserInfo(response);
                        sessionStorage.setItem('user', JSON.stringify(response));
                        $cookies.user = response;
                        sessionStorage.setItem("token", response.token);
                        localStorage.setItem('user', JSON.stringify(response));
                        localStorage.setItem('token', response.token);
                        localStorage.setItem('curTime', new Date().getTime());
                        deferred.resolve();
                    }, function(response) {
                        deferred.reject();
                    });
                    return deferred.promise;
                }
                this.login = function(userid) {
                        var deferred = $q.defer();
                        commonUserApi.login(userid).$promise.then(function(response) {
                            _self.restorageUserInfo(response);
                            sessionStorage.setItem('user', JSON.stringify(response));
                            $cookies.user = response;
                            sessionStorage.setItem("token", response.token);
                            deferred.resolve();
                        }, function(response) {
                            deferred.reject();
                        });
                        return deferred.promise;
                    }
                    /*this.login = function(){
                        var login_url = 'http://devqyftapi.snsshop.net/common/user/login?userid=452';
                    }*/
            }
        ])
        .factory('modalExtension', ['ModalService', '$q', function(ModalService, $q) {
            return {
                alert: function(msg, btnConfirmText) {
                    var deferred;
                    deferred = $q.defer();
                    ModalService.showModal({
                        templateUrl: "../../views/alert.html",
                        controller: ['$scope', 'close', 'msg', 'btnConfirmText', function($scope, close, msg, btnConfirmText) {
                            $scope.msg = msg;
                            $scope.btnConfirmText = btnConfirmText;
                            $scope.close = function() {
                                close(null, 0);
                            }
                            $scope.$on('$routeChangeStart', function(e) {
                                $scope.close();
                            })
                        }],
                        inputs: {
                            "msg": msg,
                            "btnConfirmText": btnConfirmText
                        }
                    }).then(function(modal) {
                        modal.close.then(function(result) {
                            deferred.resolve();
                        });
                    });
                    return deferred.promise;
                },
                confirm: function(msg) {
                    var deferred;
                    deferred = $q.defer();
                    ModalService.showModal({
                        templateUrl: "../../views/confirm.html",
                        controller: ['$scope', 'close', 'msg', function($scope, close, msg) {
                            $scope.msg = msg;
                            $scope.close = function() {
                                    close(null, 0);
                                },
                                $scope.close1 = function() {
                                    $('.modal').remove();
                                },
                                $scope.$on('$routeChangeStart', function(e) {
                                    $scope.close();
                                })
                        }],
                        inputs: {
                            "msg": msg
                        }
                    }).then(function(modal) {
                        modal.close.then(function(result) {
                            deferred.resolve();
                        });
                    });
                    return deferred.promise;
                },
                loading: function(msg) {
                    var result = {
                        fun: {},
                        close: function() {
                            this.fun(null, 0);
                        }
                    }
                    ModalService.showModal({
                        templateUrl: "../../views/loading.html",
                        controller: ['$scope', 'close', 'msg', 'result', function($scope, close, msg, result) {
                            $scope.msg = msg;
                            result.fun = close;
                            $scope.$on('$routeChangeStart', function(e) {
                                close(null, 0);
                            })
                        }],
                        inputs: {
                            "msg": msg,
                            "result": result
                        }
                    });
                    return result;
                },
                tips: function(msg) {
                    var result = {
                        fun: {},
                        close: function() {
                            this.fun(null, 0);
                        }
                    }
                    ModalService.showModal({
                        templateUrl: "../../views/tips.html",
                        controller: ['$scope', '$timeout', 'close', 'msg', function($scope, $timeout, close, msg) {
                            $scope.msg = msg;
                            $timeout(function() {
                                close(null, 0);
                            }, 1500);
                        }],
                        inputs: {
                            "msg": msg
                        }
                    });
                    return result;
                },
                error: function(msg) {
                    ModalService.showModal({
                        templateUrl: "../../views/error.html",
                        controller: ['$scope', '$timeout', 'close', 'msg', function($scope, $timeout, close, msg) {
                            $scope.msg = msg;
                            $timeout(function() {
                                close(null, 0);
                            }, 2000)
                            $scope.$on('$routeChangeStart', function(e) {
                                close(null, 0);
                            })
                        }],
                        inputs: {
                            "msg": msg
                        }
                    });
                }
            }
        }])
        .factory('weChat', ['$http', '$q', '$timeout', 'weChatConfig', 'globalConfig', function($http, $q, $timeout, weChatConfig, globalConfig) {
            var deferred = $q.defer();
            //标识微信JS接口状态，wx.ready执行后会变成true
            var is_ready = false;
            //判断是企业号还是企业微信
            var sUserAgent = navigator.userAgent.toLowerCase();
            var wxwork = sUserAgent.match(/wxwork/i) == "wxwork";
            if (wxwork) {
                //获取企业微信JS-SDK使用权限签名
                $http.get(globalConfig.apiUrl + weChatConfig.qy_apiUrl + "?app_code=" + globalConfig.moduleCode + "&PHPSESSID=" + sessionStorage.token).then(function(response) {
                    var config = response.data;
                    config.debug = globalConfig.webChatDebug;
                    config.jsApiList = weChatConfig.jsApiList;
                    wx.config(config);
                });
            } else {
                //获取JS-SDK使用权限签名
                $http.get(globalConfig.apiUrl + weChatConfig.apiUrl + "?app_code=" + globalConfig.moduleCode + "&PHPSESSID=" + sessionStorage.token).then(function(response) {
                    var config = response.data;
                    config.debug = globalConfig.webChatDebug;
                    config.jsApiList = weChatConfig.jsApiList;
                    wx.config(config);
                });
            }
            wx.ready(function() {
                is_ready = true;
                deferred.resolve();
            });
            wx.error(function(err) {
                deferred.reject(err);
            });
            /*
             * 检查微信API是否是ready状态
             * 调用微信JS接口时，会检查是否已ready，如果还没有ready，则等到ready后再执行相应JS接口
             * */
            var checkReady = function() {
                    var ready_deferred = $q.defer();
                    if (is_ready == true) {
                        setTimeout(function() {
                            ready_deferred.resolve();
                        })

                    } else {
                        deferred.promise.then(function(res) {
                            ready_deferred.resolve(res);
                        }, function(err) {
                            ready_deferred.reject(err);
                        })
                    }
                    return ready_deferred.promise;
                }
                /*
                 * 执行微信JS接口的统一入口
                 * 该函数主要实现两个功能
                 * 1.统一使用checkReady函数判断是否需要延时执行
                 * 2.统一将微信的api的success和fail参数转化为$q的promise模式
                 * */
            var callJSApi = function(name, config) {
                var deferred = $q.defer();
                checkReady().then(function() {
                    wx[name](angular.extend({}, config, {
                        success: function(res) {
                            deferred.resolve(res);
                            if (!angular.isUndefined(config.success))
                                config.success(res);
                        },
                        fail: function(err) {
                            deferred.reject(err);
                            if (!angular.isUndefined(config.fail))
                                config.success(err);
                        }
                    }));
                }, function(err) {
                    deferred.reject(err);
                });
                return deferred.promise;
            }
            return {
                get readyPromise() {
                    return deferred.promise;
                },
                get isReady() {
                    return is_ready;
                },
                onMenuShareTimeline: function(config) {
                    return callJSApi('onMenuShareTimeline', config)
                },
                onMenuShareAppMessage: function(config) {
                    return callJSApi('onMenuShareAppMessage', config)
                },
                onMenuShareQQ: function(config) {
                    return callJSApi('onMenuShareQQ', config)
                },
                onMenuShareWeibo: function(config) {
                    return callJSApi('onMenuShareWeibo', config)
                },
                onMenuShareQZone: function(config) {
                    return callJSApi('onMenuShareQZone', config)
                },
                startRecord: function(config) {
                    return callJSApi('startRecord', config)
                },
                stopRecord: function(config) {
                    return callJSApi('stopRecord', config)
                },
                onVoiceRecordEnd: function(config) {
                    return callJSApi('onVoiceRecordEnd', config)
                },
                playVoice: function(config) {
                    return callJSApi('playVoice', config)
                },
                pauseVoice: function(config) {
                    return callJSApi('pauseVoice', config)
                },
                stopVoice: function(config) {
                    return callJSApi('stopVoice', config)
                },
                onVoicePlayEnd: function(config) {
                    return callJSApi('onVoicePlayEnd', config)
                },
                uploadVoice: function(config) {
                    return callJSApi('uploadVoice', config)
                },
                downloadVoice: function(config) {
                    return callJSApi('downloadVoice', config)
                },
                chooseImage: function(config) {
                    return callJSApi('chooseImage', config)
                },
                previewImage: function(config) {
                    return callJSApi('previewImage', config)
                },
                uploadImage: function(config) {
                    return callJSApi('uploadImage', config)
                },
                downloadImage: function(config) {
                    return callJSApi('downloadImage', config)
                },
                translateVoice: function(config) {
                    return callJSApi('translateVoice', config)
                },
                getNetworkType: function(config) {
                    return callJSApi('getNetworkType', config)
                },
                openLocation: function(config) {
                    return callJSApi('openLocation', config)
                },
                getLocation: function(config) {
                    return callJSApi('getLocation', config)
                },
                hideOptionMenu: function(config) {
                    return callJSApi('hideOptionMenu', config)
                },
                showOptionMenu: function(config) {
                    return callJSApi('showOptionMenu', config)
                },
                hideMenuItems: function(config) {
                    return callJSApi('hideMenuItems', config)
                },
                showMenuItems: function(config) {
                    return callJSApi('showMenuItems', config)
                },
                hideAllNonBaseMenuItem: function(config) {
                    return callJSApi('hideAllNonBaseMenuItem', config)
                },
                showAllNonBaseMenuItem: function(config) {
                    return callJSApi('showAllNonBaseMenuItem', config)
                },
                closeWindow: function(config) {
                    return callJSApi('closeWindow', config)
                },
                scanQRCode: function(config) {
                    return callJSApi('scanQRCode', config)
                }
            }
        }])
        .factory('ModalService', ['$animate', '$document', '$compile', '$controller', '$http', '$rootScope', '$q', '$templateRequest', '$timeout',
            function($animate, $document, $compile, $controller, $http, $rootScope, $q, $templateRequest, $timeout) {

                //  Get the body of the document, we'll add the modal to this.
                var body = $document.find('body');

                function ModalService() {

                    var self = this;

                    //  Returns a promise which gets the template, either
                    //  from the template parameter or via a request to the
                    //  template url parameter.
                    var getTemplate = function(template, templateUrl) {
                        var deferred = $q.defer();
                        if (template) {
                            deferred.resolve(template);
                        } else if (templateUrl) {
                            $templateRequest(templateUrl, true)
                                .then(function(template) {
                                    deferred.resolve(template);
                                }, function(error) {
                                    deferred.reject(error);
                                });
                        } else {
                            deferred.reject("No template or templateUrl has been specified.");
                        }
                        return deferred.promise;
                    };

                    //  Adds an element to the DOM as the last child of its container
                    //  like append, but uses $animate to handle animations. Returns a
                    //  promise that is resolved once all animation is complete.
                    var appendChild = function(parent, child) {
                        var children = parent.children();
                        if (children.length > 0) {
                            return $animate.enter(child, parent, children[children.length - 1]);
                        }
                        return $animate.enter(child, parent);
                    };

                    self.showModal = function(options) {

                        //  Create a deferred we'll resolve when the modal is ready.
                        var deferred = $q.defer();

                        //  Validate the input parameters.
                        var controllerName = options.controller;
                        if (!controllerName) {
                            deferred.reject("No controller has been specified.");
                            return deferred.promise;
                        }

                        //  Get the actual html of the template.
                        getTemplate(options.template, options.templateUrl)
                            .then(function(template) {

                                //  Create a new scope for the modal.
                                var modalScope = $rootScope.$new();

                                //  Create the inputs object to the controller - this will include
                                //  the scope, as well as all inputs provided.
                                //  We will also create a deferred that is resolved with a provided
                                //  close function. The controller can then call 'close(result)'.
                                //  The controller can also provide a delay for closing - this is
                                //  helpful if there are closing animations which must finish first.
                                var closeDeferred = $q.defer();
                                var closedDeferred = $q.defer();
                                var inputs = {
                                    $scope: modalScope,
                                    close: function(result, delay) {
                                        if (delay === undefined || delay === null) delay = 0;
                                        $timeout(function() {
                                            //  Resolve the 'close' promise.
                                            closeDeferred.resolve(result);

                                            //  Let angular remove the element and wait for animations to finish.
                                            $animate.leave(modalElement)
                                                .then(function() {
                                                    //  Resolve the 'closed' promise.
                                                    closedDeferred.resolve(result);

                                                    //  We can now clean up the scope
                                                    modalScope.$destroy();

                                                    //  Unless we null out all of these objects we seem to suffer
                                                    //  from memory leaks, if anyone can explain why then I'd
                                                    //  be very interested to know.
                                                    inputs.close = null;
                                                    deferred = null;
                                                    closeDeferred = null;
                                                    modal = null;
                                                    inputs = null;
                                                    modalElement = null;
                                                    modalScope = null;
                                                });
                                        }, delay);
                                    }
                                };

                                //  If we have provided any inputs, pass them to the controller.
                                if (options.inputs) angular.extend(inputs, options.inputs);

                                //  Compile then link the template element, building the actual element.
                                //  Set the $element on the inputs so that it can be injected if required.
                                var linkFn = $compile(template);
                                var modalElement = linkFn(modalScope);
                                inputs.$element = modalElement;

                                //  Create the controller, explicitly specifying the scope to use.
                                var modalController = $controller(options.controller, inputs);

                                if (options.controllerAs) {
                                    modalScope[options.controllerAs] = modalController;
                                }
                                //  Finally, append the modal to the dom.
                                if (options.appendElement) {
                                    // append to custom append element
                                    appendChild(options.appendElement, modalElement);
                                } else {
                                    // append to body when no custom append element is specified
                                    appendChild(body, modalElement);
                                }

                                //  We now have a modal object...
                                var modal = {
                                    controller: modalController,
                                    scope: modalScope,
                                    element: modalElement,
                                    close: closeDeferred.promise,
                                    closed: closedDeferred.promise
                                };

                                //  ...which is passed to the caller via the promise.
                                deferred.resolve(modal);

                            })
                            .then(null, function(error) { // 'catch' doesn't work in IE8.
                                deferred.reject(error);
                            });

                        return deferred.promise;
                    };

                }

                return new ModalService();
            }
        ])
}).call(this);

;
(function() {
  angular.module('app.directives.ui', [])
      //页面切换过度动画
      .directive('pageLoading',[
          function(){
              return {
                  restrict:"A",
                  scope: {loading:"=",openLoading:"="},
                  replace:true,
                  template:'<div ng-show="loading || openLoading" class="app-content-loading"></div>',
                  link: function(scope, ele, attrs) {
                      scope.$on('$routeChangeStart', function(){
                          scope.loading = true;
                      });
                      scope.$on('$routeChangeSuccess', function(){
                          scope.loading = false;
                      });
                  }
              }

          }])
      .directive('msDate',[
          function(){
              return {
                  require:"ngModel",
                  restrict:"A",
                  link: function(scope, ele, attrs,modelCtrl) {
                      $(ele).mobiscroll().date({
                          dateFormat:'yy-mm-dd',
                          display:'bottom',
                          lang:'zh',
                          onSelect:function(value,inst){
                              modelCtrl.$setViewValue(value);
                              scope.$apply();
                          }
                      });
                      scope.$on('$routeChangeStart',function(e){
                          $(ele).mobiscroll('destroy');
                      })
                  }
              }
          }])
      .directive('msDateTime',[
          function(){
              return {
                  require:"ngModel",
                  restrict:"A",
                  link: function(scope, ele, attrs,modelCtrl) {
                      $(ele).mobiscroll().datetime({
                          dateFormat:'yy-mm-dd',
                          display:'bottom',
                          lang:'zh',
                          onSelect:function(value,inst){
                              modelCtrl.$setViewValue(value);
                              scope.$apply();
                          }
                      });
                      scope.$on('$routeChangeStart',function(e){
                          $(ele).mobiscroll('destroy');
                      })
                  }
              }
          }])
      .directive('msTime',[
          function(){
              return {
                  require:"ngModel",
                  restrict:"A",
                  link: function(scope, ele, attrs,modelCtrl) {
                      $(ele).mobiscroll().time({
                          display:'bottom',
                          lang:'zh',
                          onSelect:function(value,inst){
                              modelCtrl.$setViewValue(value);
                              scope.$apply();
                          }
                      });
                      scope.$on('$routeChangeStart',function(e){
                          $(ele).mobiscroll('destroy');
                      })
                  }
              }
          }])
      .directive('msSelect',[
          function(){
              return {
                  require:"ngModel",
                  restrict:"A",
                  link: function(scope, ele, attrs,modelCtrl) {
                      setTimeout(function(){
                          $(ele).mobiscroll().select({
                              display:'bottom',
                              lang:'zh',
                              multiple:attrs.multiple,
                              placeholder:attrs.placeholder,
                              onSelect:function(value,inst){
                                  modelCtrl.$setViewValue(inst._tempValue);
                                  scope.$apply();
                              }
                          });
                      },10)
                      scope.$on('$routeChangeStart',function(e){
                          $(ele).mobiscroll('destroy');
                      })
                  }
              }
          }])
}).call(this);

;
(function() {
    'use strict';
    angular.module('app.controllers.auth',[])
        .controller('authController',['$rootScope', '$scope','$location','$route','userManager','user','globalConfig',function($rootScope,$scope,$location,$route,userManager,user,globalConfig){
            $rootScope.openLoading = true;
            var params = $location.search();
            // console.log(params);
            userManager.restorageUserInfo();
            if(user.checkAuth()){
                $rootScope.openLoading = false;
                $location.path(decodeURIComponent(params.url));
            }else {
                //var params = $location.search();
                // console.log(globalConfig.debug);
                // return;
                /*userManager.auth({
                    "corp_id": params.state,
                    "app_code": globalConfig.moduleCode,
                    "code": params.code
                }).then(function (data) {
                    $rootScope.openLoading = false;
                    $location.path(decodeURIComponent(params.url));
                })*/
                var result;
                //console.log('id',params);
                if (globalConfig.debug == true) {
                    result = userManager.login({
                        "userid": params.id
                    });                 
                } else {
                    if (!params.code) {
                        userManager.gotoAuth(params.corp_id);
                        return;
                    }
                    result = userManager.auth({
                        "corp_id": params.state,
                        "app_code": globalConfig.moduleCode,
                        "code": params.code
                    });
                }
                result.then(function (data) {                    
                    $rootScope.openLoading = false;
                    $location.path(decodeURIComponent(params.url));
                });
            }
        }])
        .controller('qyauthController', ['$rootScope', '$scope', '$location','$window', '$route', 'userManager', 'user', 'globalConfig', function($rootScope, $scope, $location,$window, $route, userManager, user, globalConfig) {
            // $rootScope.openLoading = true;
            var params = $location.search();
            userManager.restorageUserInfo();
            //判断移动还是pc
            $scope.browserRedirect = function() {
                var sUserAgent = navigator.userAgent.toLowerCase();
                var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
                var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
                var bIsMidp = sUserAgent.match(/midp/i) == "midp";
                var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
                var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
                var bIsAndroid = sUserAgent.match(/android/i) == "android";
                var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
                var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
                var bIsWwechat = sUserAgent.match(/windowswechat/i) == "windowswechat";
                if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) {
                    //移动端企业微信
                    $location.path(decodeURIComponent(params.url));
                } else if (bIsWwechat) {
                    //pc企业微信
                    $window.location.href = globalConfig.url+'/pc/#/'+decodeURIComponent(params.pc_url);
                    // location.href = 'http://qy.vikduo.com/pc/#/index/conference/list';
                } else {
                    //pc其他浏览器
                    $scope.aa = 'pc浏览器';
                }
            };

            if (user.checkAuth()) {
                $rootScope.openLoading = false;
                $scope.browserRedirect();
            } else {
                var result;

                // $scope.bb = globalConfig.corpID + '    '+ globalConfig.moduleCode;

                if (!params.code) {
                    userManager.gotoqyAuth(globalConfig.corpID);
                    return;
                }

                result = userManager.qyauth({
                    "auth_code": params.code,
                    "app_code": globalConfig.moduleCode
                });
                result.then(function(data) {
                    $rootScope.openLoading = false;
                    $scope.browserRedirect();
                });
            }
        }])
}).call(this);

