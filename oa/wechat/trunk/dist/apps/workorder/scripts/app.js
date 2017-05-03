/*! jQuery v2.1.4 | (c) 2005, 2015 jQuery Foundation, Inc. | jquery.org/license */
!function(a,b){"object"==typeof module&&"object"==typeof module.exports?module.exports=a.document?b(a,!0):function(a){if(!a.document)throw new Error("jQuery requires a window with a document");return b(a)}:b(a)}("undefined"!=typeof window?window:this,function(a,b){var c=[],d=c.slice,e=c.concat,f=c.push,g=c.indexOf,h={},i=h.toString,j=h.hasOwnProperty,k={},l=a.document,m="2.1.4",n=function(a,b){return new n.fn.init(a,b)},o=/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,p=/^-ms-/,q=/-([\da-z])/gi,r=function(a,b){return b.toUpperCase()};n.fn=n.prototype={jquery:m,constructor:n,selector:"",length:0,toArray:function(){return d.call(this)},get:function(a){return null!=a?0>a?this[a+this.length]:this[a]:d.call(this)},pushStack:function(a){var b=n.merge(this.constructor(),a);return b.prevObject=this,b.context=this.context,b},each:function(a,b){return n.each(this,a,b)},map:function(a){return this.pushStack(n.map(this,function(b,c){return a.call(b,c,b)}))},slice:function(){return this.pushStack(d.apply(this,arguments))},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},eq:function(a){var b=this.length,c=+a+(0>a?b:0);return this.pushStack(c>=0&&b>c?[this[c]]:[])},end:function(){return this.prevObject||this.constructor(null)},push:f,sort:c.sort,splice:c.splice},n.extend=n.fn.extend=function(){var a,b,c,d,e,f,g=arguments[0]||{},h=1,i=arguments.length,j=!1;for("boolean"==typeof g&&(j=g,g=arguments[h]||{},h++),"object"==typeof g||n.isFunction(g)||(g={}),h===i&&(g=this,h--);i>h;h++)if(null!=(a=arguments[h]))for(b in a)c=g[b],d=a[b],g!==d&&(j&&d&&(n.isPlainObject(d)||(e=n.isArray(d)))?(e?(e=!1,f=c&&n.isArray(c)?c:[]):f=c&&n.isPlainObject(c)?c:{},g[b]=n.extend(j,f,d)):void 0!==d&&(g[b]=d));return g},n.extend({expando:"jQuery"+(m+Math.random()).replace(/\D/g,""),isReady:!0,error:function(a){throw new Error(a)},noop:function(){},isFunction:function(a){return"function"===n.type(a)},isArray:Array.isArray,isWindow:function(a){return null!=a&&a===a.window},isNumeric:function(a){return!n.isArray(a)&&a-parseFloat(a)+1>=0},isPlainObject:function(a){return"object"!==n.type(a)||a.nodeType||n.isWindow(a)?!1:a.constructor&&!j.call(a.constructor.prototype,"isPrototypeOf")?!1:!0},isEmptyObject:function(a){var b;for(b in a)return!1;return!0},type:function(a){return null==a?a+"":"object"==typeof a||"function"==typeof a?h[i.call(a)]||"object":typeof a},globalEval:function(a){var b,c=eval;a=n.trim(a),a&&(1===a.indexOf("use strict")?(b=l.createElement("script"),b.text=a,l.head.appendChild(b).parentNode.removeChild(b)):c(a))},camelCase:function(a){return a.replace(p,"ms-").replace(q,r)},nodeName:function(a,b){return a.nodeName&&a.nodeName.toLowerCase()===b.toLowerCase()},each:function(a,b,c){var d,e=0,f=a.length,g=s(a);if(c){if(g){for(;f>e;e++)if(d=b.apply(a[e],c),d===!1)break}else for(e in a)if(d=b.apply(a[e],c),d===!1)break}else if(g){for(;f>e;e++)if(d=b.call(a[e],e,a[e]),d===!1)break}else for(e in a)if(d=b.call(a[e],e,a[e]),d===!1)break;return a},trim:function(a){return null==a?"":(a+"").replace(o,"")},makeArray:function(a,b){var c=b||[];return null!=a&&(s(Object(a))?n.merge(c,"string"==typeof a?[a]:a):f.call(c,a)),c},inArray:function(a,b,c){return null==b?-1:g.call(b,a,c)},merge:function(a,b){for(var c=+b.length,d=0,e=a.length;c>d;d++)a[e++]=b[d];return a.length=e,a},grep:function(a,b,c){for(var d,e=[],f=0,g=a.length,h=!c;g>f;f++)d=!b(a[f],f),d!==h&&e.push(a[f]);return e},map:function(a,b,c){var d,f=0,g=a.length,h=s(a),i=[];if(h)for(;g>f;f++)d=b(a[f],f,c),null!=d&&i.push(d);else for(f in a)d=b(a[f],f,c),null!=d&&i.push(d);return e.apply([],i)},guid:1,proxy:function(a,b){var c,e,f;return"string"==typeof b&&(c=a[b],b=a,a=c),n.isFunction(a)?(e=d.call(arguments,2),f=function(){return a.apply(b||this,e.concat(d.call(arguments)))},f.guid=a.guid=a.guid||n.guid++,f):void 0},now:Date.now,support:k}),n.each("Boolean Number String Function Array Date RegExp Object Error".split(" "),function(a,b){h["[object "+b+"]"]=b.toLowerCase()});function s(a){var b="length"in a&&a.length,c=n.type(a);return"function"===c||n.isWindow(a)?!1:1===a.nodeType&&b?!0:"array"===c||0===b||"number"==typeof b&&b>0&&b-1 in a}var t=function(a){var b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u="sizzle"+1*new Date,v=a.document,w=0,x=0,y=ha(),z=ha(),A=ha(),B=function(a,b){return a===b&&(l=!0),0},C=1<<31,D={}.hasOwnProperty,E=[],F=E.pop,G=E.push,H=E.push,I=E.slice,J=function(a,b){for(var c=0,d=a.length;d>c;c++)if(a[c]===b)return c;return-1},K="checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",L="[\\x20\\t\\r\\n\\f]",M="(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",N=M.replace("w","w#"),O="\\["+L+"*("+M+")(?:"+L+"*([*^$|!~]?=)"+L+"*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|("+N+"))|)"+L+"*\\]",P=":("+M+")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|"+O+")*)|.*)\\)|)",Q=new RegExp(L+"+","g"),R=new RegExp("^"+L+"+|((?:^|[^\\\\])(?:\\\\.)*)"+L+"+$","g"),S=new RegExp("^"+L+"*,"+L+"*"),T=new RegExp("^"+L+"*([>+~]|"+L+")"+L+"*"),U=new RegExp("="+L+"*([^\\]'\"]*?)"+L+"*\\]","g"),V=new RegExp(P),W=new RegExp("^"+N+"$"),X={ID:new RegExp("^#("+M+")"),CLASS:new RegExp("^\\.("+M+")"),TAG:new RegExp("^("+M.replace("w","w*")+")"),ATTR:new RegExp("^"+O),PSEUDO:new RegExp("^"+P),CHILD:new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\("+L+"*(even|odd|(([+-]|)(\\d*)n|)"+L+"*(?:([+-]|)"+L+"*(\\d+)|))"+L+"*\\)|)","i"),bool:new RegExp("^(?:"+K+")$","i"),needsContext:new RegExp("^"+L+"*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\("+L+"*((?:-\\d)?\\d*)"+L+"*\\)|)(?=[^-]|$)","i")},Y=/^(?:input|select|textarea|button)$/i,Z=/^h\d$/i,$=/^[^{]+\{\s*\[native \w/,_=/^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,aa=/[+~]/,ba=/'|\\/g,ca=new RegExp("\\\\([\\da-f]{1,6}"+L+"?|("+L+")|.)","ig"),da=function(a,b,c){var d="0x"+b-65536;return d!==d||c?b:0>d?String.fromCharCode(d+65536):String.fromCharCode(d>>10|55296,1023&d|56320)},ea=function(){m()};try{H.apply(E=I.call(v.childNodes),v.childNodes),E[v.childNodes.length].nodeType}catch(fa){H={apply:E.length?function(a,b){G.apply(a,I.call(b))}:function(a,b){var c=a.length,d=0;while(a[c++]=b[d++]);a.length=c-1}}}function ga(a,b,d,e){var f,h,j,k,l,o,r,s,w,x;if((b?b.ownerDocument||b:v)!==n&&m(b),b=b||n,d=d||[],k=b.nodeType,"string"!=typeof a||!a||1!==k&&9!==k&&11!==k)return d;if(!e&&p){if(11!==k&&(f=_.exec(a)))if(j=f[1]){if(9===k){if(h=b.getElementById(j),!h||!h.parentNode)return d;if(h.id===j)return d.push(h),d}else if(b.ownerDocument&&(h=b.ownerDocument.getElementById(j))&&t(b,h)&&h.id===j)return d.push(h),d}else{if(f[2])return H.apply(d,b.getElementsByTagName(a)),d;if((j=f[3])&&c.getElementsByClassName)return H.apply(d,b.getElementsByClassName(j)),d}if(c.qsa&&(!q||!q.test(a))){if(s=r=u,w=b,x=1!==k&&a,1===k&&"object"!==b.nodeName.toLowerCase()){o=g(a),(r=b.getAttribute("id"))?s=r.replace(ba,"\\$&"):b.setAttribute("id",s),s="[id='"+s+"'] ",l=o.length;while(l--)o[l]=s+ra(o[l]);w=aa.test(a)&&pa(b.parentNode)||b,x=o.join(",")}if(x)try{return H.apply(d,w.querySelectorAll(x)),d}catch(y){}finally{r||b.removeAttribute("id")}}}return i(a.replace(R,"$1"),b,d,e)}function ha(){var a=[];function b(c,e){return a.push(c+" ")>d.cacheLength&&delete b[a.shift()],b[c+" "]=e}return b}function ia(a){return a[u]=!0,a}function ja(a){var b=n.createElement("div");try{return!!a(b)}catch(c){return!1}finally{b.parentNode&&b.parentNode.removeChild(b),b=null}}function ka(a,b){var c=a.split("|"),e=a.length;while(e--)d.attrHandle[c[e]]=b}function la(a,b){var c=b&&a,d=c&&1===a.nodeType&&1===b.nodeType&&(~b.sourceIndex||C)-(~a.sourceIndex||C);if(d)return d;if(c)while(c=c.nextSibling)if(c===b)return-1;return a?1:-1}function ma(a){return function(b){var c=b.nodeName.toLowerCase();return"input"===c&&b.type===a}}function na(a){return function(b){var c=b.nodeName.toLowerCase();return("input"===c||"button"===c)&&b.type===a}}function oa(a){return ia(function(b){return b=+b,ia(function(c,d){var e,f=a([],c.length,b),g=f.length;while(g--)c[e=f[g]]&&(c[e]=!(d[e]=c[e]))})})}function pa(a){return a&&"undefined"!=typeof a.getElementsByTagName&&a}c=ga.support={},f=ga.isXML=function(a){var b=a&&(a.ownerDocument||a).documentElement;return b?"HTML"!==b.nodeName:!1},m=ga.setDocument=function(a){var b,e,g=a?a.ownerDocument||a:v;return g!==n&&9===g.nodeType&&g.documentElement?(n=g,o=g.documentElement,e=g.defaultView,e&&e!==e.top&&(e.addEventListener?e.addEventListener("unload",ea,!1):e.attachEvent&&e.attachEvent("onunload",ea)),p=!f(g),c.attributes=ja(function(a){return a.className="i",!a.getAttribute("className")}),c.getElementsByTagName=ja(function(a){return a.appendChild(g.createComment("")),!a.getElementsByTagName("*").length}),c.getElementsByClassName=$.test(g.getElementsByClassName),c.getById=ja(function(a){return o.appendChild(a).id=u,!g.getElementsByName||!g.getElementsByName(u).length}),c.getById?(d.find.ID=function(a,b){if("undefined"!=typeof b.getElementById&&p){var c=b.getElementById(a);return c&&c.parentNode?[c]:[]}},d.filter.ID=function(a){var b=a.replace(ca,da);return function(a){return a.getAttribute("id")===b}}):(delete d.find.ID,d.filter.ID=function(a){var b=a.replace(ca,da);return function(a){var c="undefined"!=typeof a.getAttributeNode&&a.getAttributeNode("id");return c&&c.value===b}}),d.find.TAG=c.getElementsByTagName?function(a,b){return"undefined"!=typeof b.getElementsByTagName?b.getElementsByTagName(a):c.qsa?b.querySelectorAll(a):void 0}:function(a,b){var c,d=[],e=0,f=b.getElementsByTagName(a);if("*"===a){while(c=f[e++])1===c.nodeType&&d.push(c);return d}return f},d.find.CLASS=c.getElementsByClassName&&function(a,b){return p?b.getElementsByClassName(a):void 0},r=[],q=[],(c.qsa=$.test(g.querySelectorAll))&&(ja(function(a){o.appendChild(a).innerHTML="<a id='"+u+"'></a><select id='"+u+"-\f]' msallowcapture=''><option selected=''></option></select>",a.querySelectorAll("[msallowcapture^='']").length&&q.push("[*^$]="+L+"*(?:''|\"\")"),a.querySelectorAll("[selected]").length||q.push("\\["+L+"*(?:value|"+K+")"),a.querySelectorAll("[id~="+u+"-]").length||q.push("~="),a.querySelectorAll(":checked").length||q.push(":checked"),a.querySelectorAll("a#"+u+"+*").length||q.push(".#.+[+~]")}),ja(function(a){var b=g.createElement("input");b.setAttribute("type","hidden"),a.appendChild(b).setAttribute("name","D"),a.querySelectorAll("[name=d]").length&&q.push("name"+L+"*[*^$|!~]?="),a.querySelectorAll(":enabled").length||q.push(":enabled",":disabled"),a.querySelectorAll("*,:x"),q.push(",.*:")})),(c.matchesSelector=$.test(s=o.matches||o.webkitMatchesSelector||o.mozMatchesSelector||o.oMatchesSelector||o.msMatchesSelector))&&ja(function(a){c.disconnectedMatch=s.call(a,"div"),s.call(a,"[s!='']:x"),r.push("!=",P)}),q=q.length&&new RegExp(q.join("|")),r=r.length&&new RegExp(r.join("|")),b=$.test(o.compareDocumentPosition),t=b||$.test(o.contains)?function(a,b){var c=9===a.nodeType?a.documentElement:a,d=b&&b.parentNode;return a===d||!(!d||1!==d.nodeType||!(c.contains?c.contains(d):a.compareDocumentPosition&&16&a.compareDocumentPosition(d)))}:function(a,b){if(b)while(b=b.parentNode)if(b===a)return!0;return!1},B=b?function(a,b){if(a===b)return l=!0,0;var d=!a.compareDocumentPosition-!b.compareDocumentPosition;return d?d:(d=(a.ownerDocument||a)===(b.ownerDocument||b)?a.compareDocumentPosition(b):1,1&d||!c.sortDetached&&b.compareDocumentPosition(a)===d?a===g||a.ownerDocument===v&&t(v,a)?-1:b===g||b.ownerDocument===v&&t(v,b)?1:k?J(k,a)-J(k,b):0:4&d?-1:1)}:function(a,b){if(a===b)return l=!0,0;var c,d=0,e=a.parentNode,f=b.parentNode,h=[a],i=[b];if(!e||!f)return a===g?-1:b===g?1:e?-1:f?1:k?J(k,a)-J(k,b):0;if(e===f)return la(a,b);c=a;while(c=c.parentNode)h.unshift(c);c=b;while(c=c.parentNode)i.unshift(c);while(h[d]===i[d])d++;return d?la(h[d],i[d]):h[d]===v?-1:i[d]===v?1:0},g):n},ga.matches=function(a,b){return ga(a,null,null,b)},ga.matchesSelector=function(a,b){if((a.ownerDocument||a)!==n&&m(a),b=b.replace(U,"='$1']"),!(!c.matchesSelector||!p||r&&r.test(b)||q&&q.test(b)))try{var d=s.call(a,b);if(d||c.disconnectedMatch||a.document&&11!==a.document.nodeType)return d}catch(e){}return ga(b,n,null,[a]).length>0},ga.contains=function(a,b){return(a.ownerDocument||a)!==n&&m(a),t(a,b)},ga.attr=function(a,b){(a.ownerDocument||a)!==n&&m(a);var e=d.attrHandle[b.toLowerCase()],f=e&&D.call(d.attrHandle,b.toLowerCase())?e(a,b,!p):void 0;return void 0!==f?f:c.attributes||!p?a.getAttribute(b):(f=a.getAttributeNode(b))&&f.specified?f.value:null},ga.error=function(a){throw new Error("Syntax error, unrecognized expression: "+a)},ga.uniqueSort=function(a){var b,d=[],e=0,f=0;if(l=!c.detectDuplicates,k=!c.sortStable&&a.slice(0),a.sort(B),l){while(b=a[f++])b===a[f]&&(e=d.push(f));while(e--)a.splice(d[e],1)}return k=null,a},e=ga.getText=function(a){var b,c="",d=0,f=a.nodeType;if(f){if(1===f||9===f||11===f){if("string"==typeof a.textContent)return a.textContent;for(a=a.firstChild;a;a=a.nextSibling)c+=e(a)}else if(3===f||4===f)return a.nodeValue}else while(b=a[d++])c+=e(b);return c},d=ga.selectors={cacheLength:50,createPseudo:ia,match:X,attrHandle:{},find:{},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(a){return a[1]=a[1].replace(ca,da),a[3]=(a[3]||a[4]||a[5]||"").replace(ca,da),"~="===a[2]&&(a[3]=" "+a[3]+" "),a.slice(0,4)},CHILD:function(a){return a[1]=a[1].toLowerCase(),"nth"===a[1].slice(0,3)?(a[3]||ga.error(a[0]),a[4]=+(a[4]?a[5]+(a[6]||1):2*("even"===a[3]||"odd"===a[3])),a[5]=+(a[7]+a[8]||"odd"===a[3])):a[3]&&ga.error(a[0]),a},PSEUDO:function(a){var b,c=!a[6]&&a[2];return X.CHILD.test(a[0])?null:(a[3]?a[2]=a[4]||a[5]||"":c&&V.test(c)&&(b=g(c,!0))&&(b=c.indexOf(")",c.length-b)-c.length)&&(a[0]=a[0].slice(0,b),a[2]=c.slice(0,b)),a.slice(0,3))}},filter:{TAG:function(a){var b=a.replace(ca,da).toLowerCase();return"*"===a?function(){return!0}:function(a){return a.nodeName&&a.nodeName.toLowerCase()===b}},CLASS:function(a){var b=y[a+" "];return b||(b=new RegExp("(^|"+L+")"+a+"("+L+"|$)"))&&y(a,function(a){return b.test("string"==typeof a.className&&a.className||"undefined"!=typeof a.getAttribute&&a.getAttribute("class")||"")})},ATTR:function(a,b,c){return function(d){var e=ga.attr(d,a);return null==e?"!="===b:b?(e+="","="===b?e===c:"!="===b?e!==c:"^="===b?c&&0===e.indexOf(c):"*="===b?c&&e.indexOf(c)>-1:"$="===b?c&&e.slice(-c.length)===c:"~="===b?(" "+e.replace(Q," ")+" ").indexOf(c)>-1:"|="===b?e===c||e.slice(0,c.length+1)===c+"-":!1):!0}},CHILD:function(a,b,c,d,e){var f="nth"!==a.slice(0,3),g="last"!==a.slice(-4),h="of-type"===b;return 1===d&&0===e?function(a){return!!a.parentNode}:function(b,c,i){var j,k,l,m,n,o,p=f!==g?"nextSibling":"previousSibling",q=b.parentNode,r=h&&b.nodeName.toLowerCase(),s=!i&&!h;if(q){if(f){while(p){l=b;while(l=l[p])if(h?l.nodeName.toLowerCase()===r:1===l.nodeType)return!1;o=p="only"===a&&!o&&"nextSibling"}return!0}if(o=[g?q.firstChild:q.lastChild],g&&s){k=q[u]||(q[u]={}),j=k[a]||[],n=j[0]===w&&j[1],m=j[0]===w&&j[2],l=n&&q.childNodes[n];while(l=++n&&l&&l[p]||(m=n=0)||o.pop())if(1===l.nodeType&&++m&&l===b){k[a]=[w,n,m];break}}else if(s&&(j=(b[u]||(b[u]={}))[a])&&j[0]===w)m=j[1];else while(l=++n&&l&&l[p]||(m=n=0)||o.pop())if((h?l.nodeName.toLowerCase()===r:1===l.nodeType)&&++m&&(s&&((l[u]||(l[u]={}))[a]=[w,m]),l===b))break;return m-=e,m===d||m%d===0&&m/d>=0}}},PSEUDO:function(a,b){var c,e=d.pseudos[a]||d.setFilters[a.toLowerCase()]||ga.error("unsupported pseudo: "+a);return e[u]?e(b):e.length>1?(c=[a,a,"",b],d.setFilters.hasOwnProperty(a.toLowerCase())?ia(function(a,c){var d,f=e(a,b),g=f.length;while(g--)d=J(a,f[g]),a[d]=!(c[d]=f[g])}):function(a){return e(a,0,c)}):e}},pseudos:{not:ia(function(a){var b=[],c=[],d=h(a.replace(R,"$1"));return d[u]?ia(function(a,b,c,e){var f,g=d(a,null,e,[]),h=a.length;while(h--)(f=g[h])&&(a[h]=!(b[h]=f))}):function(a,e,f){return b[0]=a,d(b,null,f,c),b[0]=null,!c.pop()}}),has:ia(function(a){return function(b){return ga(a,b).length>0}}),contains:ia(function(a){return a=a.replace(ca,da),function(b){return(b.textContent||b.innerText||e(b)).indexOf(a)>-1}}),lang:ia(function(a){return W.test(a||"")||ga.error("unsupported lang: "+a),a=a.replace(ca,da).toLowerCase(),function(b){var c;do if(c=p?b.lang:b.getAttribute("xml:lang")||b.getAttribute("lang"))return c=c.toLowerCase(),c===a||0===c.indexOf(a+"-");while((b=b.parentNode)&&1===b.nodeType);return!1}}),target:function(b){var c=a.location&&a.location.hash;return c&&c.slice(1)===b.id},root:function(a){return a===o},focus:function(a){return a===n.activeElement&&(!n.hasFocus||n.hasFocus())&&!!(a.type||a.href||~a.tabIndex)},enabled:function(a){return a.disabled===!1},disabled:function(a){return a.disabled===!0},checked:function(a){var b=a.nodeName.toLowerCase();return"input"===b&&!!a.checked||"option"===b&&!!a.selected},selected:function(a){return a.parentNode&&a.parentNode.selectedIndex,a.selected===!0},empty:function(a){for(a=a.firstChild;a;a=a.nextSibling)if(a.nodeType<6)return!1;return!0},parent:function(a){return!d.pseudos.empty(a)},header:function(a){return Z.test(a.nodeName)},input:function(a){return Y.test(a.nodeName)},button:function(a){var b=a.nodeName.toLowerCase();return"input"===b&&"button"===a.type||"button"===b},text:function(a){var b;return"input"===a.nodeName.toLowerCase()&&"text"===a.type&&(null==(b=a.getAttribute("type"))||"text"===b.toLowerCase())},first:oa(function(){return[0]}),last:oa(function(a,b){return[b-1]}),eq:oa(function(a,b,c){return[0>c?c+b:c]}),even:oa(function(a,b){for(var c=0;b>c;c+=2)a.push(c);return a}),odd:oa(function(a,b){for(var c=1;b>c;c+=2)a.push(c);return a}),lt:oa(function(a,b,c){for(var d=0>c?c+b:c;--d>=0;)a.push(d);return a}),gt:oa(function(a,b,c){for(var d=0>c?c+b:c;++d<b;)a.push(d);return a})}},d.pseudos.nth=d.pseudos.eq;for(b in{radio:!0,checkbox:!0,file:!0,password:!0,image:!0})d.pseudos[b]=ma(b);for(b in{submit:!0,reset:!0})d.pseudos[b]=na(b);function qa(){}qa.prototype=d.filters=d.pseudos,d.setFilters=new qa,g=ga.tokenize=function(a,b){var c,e,f,g,h,i,j,k=z[a+" "];if(k)return b?0:k.slice(0);h=a,i=[],j=d.preFilter;while(h){(!c||(e=S.exec(h)))&&(e&&(h=h.slice(e[0].length)||h),i.push(f=[])),c=!1,(e=T.exec(h))&&(c=e.shift(),f.push({value:c,type:e[0].replace(R," ")}),h=h.slice(c.length));for(g in d.filter)!(e=X[g].exec(h))||j[g]&&!(e=j[g](e))||(c=e.shift(),f.push({value:c,type:g,matches:e}),h=h.slice(c.length));if(!c)break}return b?h.length:h?ga.error(a):z(a,i).slice(0)};function ra(a){for(var b=0,c=a.length,d="";c>b;b++)d+=a[b].value;return d}function sa(a,b,c){var d=b.dir,e=c&&"parentNode"===d,f=x++;return b.first?function(b,c,f){while(b=b[d])if(1===b.nodeType||e)return a(b,c,f)}:function(b,c,g){var h,i,j=[w,f];if(g){while(b=b[d])if((1===b.nodeType||e)&&a(b,c,g))return!0}else while(b=b[d])if(1===b.nodeType||e){if(i=b[u]||(b[u]={}),(h=i[d])&&h[0]===w&&h[1]===f)return j[2]=h[2];if(i[d]=j,j[2]=a(b,c,g))return!0}}}function ta(a){return a.length>1?function(b,c,d){var e=a.length;while(e--)if(!a[e](b,c,d))return!1;return!0}:a[0]}function ua(a,b,c){for(var d=0,e=b.length;e>d;d++)ga(a,b[d],c);return c}function va(a,b,c,d,e){for(var f,g=[],h=0,i=a.length,j=null!=b;i>h;h++)(f=a[h])&&(!c||c(f,d,e))&&(g.push(f),j&&b.push(h));return g}function wa(a,b,c,d,e,f){return d&&!d[u]&&(d=wa(d)),e&&!e[u]&&(e=wa(e,f)),ia(function(f,g,h,i){var j,k,l,m=[],n=[],o=g.length,p=f||ua(b||"*",h.nodeType?[h]:h,[]),q=!a||!f&&b?p:va(p,m,a,h,i),r=c?e||(f?a:o||d)?[]:g:q;if(c&&c(q,r,h,i),d){j=va(r,n),d(j,[],h,i),k=j.length;while(k--)(l=j[k])&&(r[n[k]]=!(q[n[k]]=l))}if(f){if(e||a){if(e){j=[],k=r.length;while(k--)(l=r[k])&&j.push(q[k]=l);e(null,r=[],j,i)}k=r.length;while(k--)(l=r[k])&&(j=e?J(f,l):m[k])>-1&&(f[j]=!(g[j]=l))}}else r=va(r===g?r.splice(o,r.length):r),e?e(null,g,r,i):H.apply(g,r)})}function xa(a){for(var b,c,e,f=a.length,g=d.relative[a[0].type],h=g||d.relative[" "],i=g?1:0,k=sa(function(a){return a===b},h,!0),l=sa(function(a){return J(b,a)>-1},h,!0),m=[function(a,c,d){var e=!g&&(d||c!==j)||((b=c).nodeType?k(a,c,d):l(a,c,d));return b=null,e}];f>i;i++)if(c=d.relative[a[i].type])m=[sa(ta(m),c)];else{if(c=d.filter[a[i].type].apply(null,a[i].matches),c[u]){for(e=++i;f>e;e++)if(d.relative[a[e].type])break;return wa(i>1&&ta(m),i>1&&ra(a.slice(0,i-1).concat({value:" "===a[i-2].type?"*":""})).replace(R,"$1"),c,e>i&&xa(a.slice(i,e)),f>e&&xa(a=a.slice(e)),f>e&&ra(a))}m.push(c)}return ta(m)}function ya(a,b){var c=b.length>0,e=a.length>0,f=function(f,g,h,i,k){var l,m,o,p=0,q="0",r=f&&[],s=[],t=j,u=f||e&&d.find.TAG("*",k),v=w+=null==t?1:Math.random()||.1,x=u.length;for(k&&(j=g!==n&&g);q!==x&&null!=(l=u[q]);q++){if(e&&l){m=0;while(o=a[m++])if(o(l,g,h)){i.push(l);break}k&&(w=v)}c&&((l=!o&&l)&&p--,f&&r.push(l))}if(p+=q,c&&q!==p){m=0;while(o=b[m++])o(r,s,g,h);if(f){if(p>0)while(q--)r[q]||s[q]||(s[q]=F.call(i));s=va(s)}H.apply(i,s),k&&!f&&s.length>0&&p+b.length>1&&ga.uniqueSort(i)}return k&&(w=v,j=t),r};return c?ia(f):f}return h=ga.compile=function(a,b){var c,d=[],e=[],f=A[a+" "];if(!f){b||(b=g(a)),c=b.length;while(c--)f=xa(b[c]),f[u]?d.push(f):e.push(f);f=A(a,ya(e,d)),f.selector=a}return f},i=ga.select=function(a,b,e,f){var i,j,k,l,m,n="function"==typeof a&&a,o=!f&&g(a=n.selector||a);if(e=e||[],1===o.length){if(j=o[0]=o[0].slice(0),j.length>2&&"ID"===(k=j[0]).type&&c.getById&&9===b.nodeType&&p&&d.relative[j[1].type]){if(b=(d.find.ID(k.matches[0].replace(ca,da),b)||[])[0],!b)return e;n&&(b=b.parentNode),a=a.slice(j.shift().value.length)}i=X.needsContext.test(a)?0:j.length;while(i--){if(k=j[i],d.relative[l=k.type])break;if((m=d.find[l])&&(f=m(k.matches[0].replace(ca,da),aa.test(j[0].type)&&pa(b.parentNode)||b))){if(j.splice(i,1),a=f.length&&ra(j),!a)return H.apply(e,f),e;break}}}return(n||h(a,o))(f,b,!p,e,aa.test(a)&&pa(b.parentNode)||b),e},c.sortStable=u.split("").sort(B).join("")===u,c.detectDuplicates=!!l,m(),c.sortDetached=ja(function(a){return 1&a.compareDocumentPosition(n.createElement("div"))}),ja(function(a){return a.innerHTML="<a href='#'></a>","#"===a.firstChild.getAttribute("href")})||ka("type|href|height|width",function(a,b,c){return c?void 0:a.getAttribute(b,"type"===b.toLowerCase()?1:2)}),c.attributes&&ja(function(a){return a.innerHTML="<input/>",a.firstChild.setAttribute("value",""),""===a.firstChild.getAttribute("value")})||ka("value",function(a,b,c){return c||"input"!==a.nodeName.toLowerCase()?void 0:a.defaultValue}),ja(function(a){return null==a.getAttribute("disabled")})||ka(K,function(a,b,c){var d;return c?void 0:a[b]===!0?b.toLowerCase():(d=a.getAttributeNode(b))&&d.specified?d.value:null}),ga}(a);n.find=t,n.expr=t.selectors,n.expr[":"]=n.expr.pseudos,n.unique=t.uniqueSort,n.text=t.getText,n.isXMLDoc=t.isXML,n.contains=t.contains;var u=n.expr.match.needsContext,v=/^<(\w+)\s*\/?>(?:<\/\1>|)$/,w=/^.[^:#\[\.,]*$/;function x(a,b,c){if(n.isFunction(b))return n.grep(a,function(a,d){return!!b.call(a,d,a)!==c});if(b.nodeType)return n.grep(a,function(a){return a===b!==c});if("string"==typeof b){if(w.test(b))return n.filter(b,a,c);b=n.filter(b,a)}return n.grep(a,function(a){return g.call(b,a)>=0!==c})}n.filter=function(a,b,c){var d=b[0];return c&&(a=":not("+a+")"),1===b.length&&1===d.nodeType?n.find.matchesSelector(d,a)?[d]:[]:n.find.matches(a,n.grep(b,function(a){return 1===a.nodeType}))},n.fn.extend({find:function(a){var b,c=this.length,d=[],e=this;if("string"!=typeof a)return this.pushStack(n(a).filter(function(){for(b=0;c>b;b++)if(n.contains(e[b],this))return!0}));for(b=0;c>b;b++)n.find(a,e[b],d);return d=this.pushStack(c>1?n.unique(d):d),d.selector=this.selector?this.selector+" "+a:a,d},filter:function(a){return this.pushStack(x(this,a||[],!1))},not:function(a){return this.pushStack(x(this,a||[],!0))},is:function(a){return!!x(this,"string"==typeof a&&u.test(a)?n(a):a||[],!1).length}});var y,z=/^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/,A=n.fn.init=function(a,b){var c,d;if(!a)return this;if("string"==typeof a){if(c="<"===a[0]&&">"===a[a.length-1]&&a.length>=3?[null,a,null]:z.exec(a),!c||!c[1]&&b)return!b||b.jquery?(b||y).find(a):this.constructor(b).find(a);if(c[1]){if(b=b instanceof n?b[0]:b,n.merge(this,n.parseHTML(c[1],b&&b.nodeType?b.ownerDocument||b:l,!0)),v.test(c[1])&&n.isPlainObject(b))for(c in b)n.isFunction(this[c])?this[c](b[c]):this.attr(c,b[c]);return this}return d=l.getElementById(c[2]),d&&d.parentNode&&(this.length=1,this[0]=d),this.context=l,this.selector=a,this}return a.nodeType?(this.context=this[0]=a,this.length=1,this):n.isFunction(a)?"undefined"!=typeof y.ready?y.ready(a):a(n):(void 0!==a.selector&&(this.selector=a.selector,this.context=a.context),n.makeArray(a,this))};A.prototype=n.fn,y=n(l);var B=/^(?:parents|prev(?:Until|All))/,C={children:!0,contents:!0,next:!0,prev:!0};n.extend({dir:function(a,b,c){var d=[],e=void 0!==c;while((a=a[b])&&9!==a.nodeType)if(1===a.nodeType){if(e&&n(a).is(c))break;d.push(a)}return d},sibling:function(a,b){for(var c=[];a;a=a.nextSibling)1===a.nodeType&&a!==b&&c.push(a);return c}}),n.fn.extend({has:function(a){var b=n(a,this),c=b.length;return this.filter(function(){for(var a=0;c>a;a++)if(n.contains(this,b[a]))return!0})},closest:function(a,b){for(var c,d=0,e=this.length,f=[],g=u.test(a)||"string"!=typeof a?n(a,b||this.context):0;e>d;d++)for(c=this[d];c&&c!==b;c=c.parentNode)if(c.nodeType<11&&(g?g.index(c)>-1:1===c.nodeType&&n.find.matchesSelector(c,a))){f.push(c);break}return this.pushStack(f.length>1?n.unique(f):f)},index:function(a){return a?"string"==typeof a?g.call(n(a),this[0]):g.call(this,a.jquery?a[0]:a):this[0]&&this[0].parentNode?this.first().prevAll().length:-1},add:function(a,b){return this.pushStack(n.unique(n.merge(this.get(),n(a,b))))},addBack:function(a){return this.add(null==a?this.prevObject:this.prevObject.filter(a))}});function D(a,b){while((a=a[b])&&1!==a.nodeType);return a}n.each({parent:function(a){var b=a.parentNode;return b&&11!==b.nodeType?b:null},parents:function(a){return n.dir(a,"parentNode")},parentsUntil:function(a,b,c){return n.dir(a,"parentNode",c)},next:function(a){return D(a,"nextSibling")},prev:function(a){return D(a,"previousSibling")},nextAll:function(a){return n.dir(a,"nextSibling")},prevAll:function(a){return n.dir(a,"previousSibling")},nextUntil:function(a,b,c){return n.dir(a,"nextSibling",c)},prevUntil:function(a,b,c){return n.dir(a,"previousSibling",c)},siblings:function(a){return n.sibling((a.parentNode||{}).firstChild,a)},children:function(a){return n.sibling(a.firstChild)},contents:function(a){return a.contentDocument||n.merge([],a.childNodes)}},function(a,b){n.fn[a]=function(c,d){var e=n.map(this,b,c);return"Until"!==a.slice(-5)&&(d=c),d&&"string"==typeof d&&(e=n.filter(d,e)),this.length>1&&(C[a]||n.unique(e),B.test(a)&&e.reverse()),this.pushStack(e)}});var E=/\S+/g,F={};function G(a){var b=F[a]={};return n.each(a.match(E)||[],function(a,c){b[c]=!0}),b}n.Callbacks=function(a){a="string"==typeof a?F[a]||G(a):n.extend({},a);var b,c,d,e,f,g,h=[],i=!a.once&&[],j=function(l){for(b=a.memory&&l,c=!0,g=e||0,e=0,f=h.length,d=!0;h&&f>g;g++)if(h[g].apply(l[0],l[1])===!1&&a.stopOnFalse){b=!1;break}d=!1,h&&(i?i.length&&j(i.shift()):b?h=[]:k.disable())},k={add:function(){if(h){var c=h.length;!function g(b){n.each(b,function(b,c){var d=n.type(c);"function"===d?a.unique&&k.has(c)||h.push(c):c&&c.length&&"string"!==d&&g(c)})}(arguments),d?f=h.length:b&&(e=c,j(b))}return this},remove:function(){return h&&n.each(arguments,function(a,b){var c;while((c=n.inArray(b,h,c))>-1)h.splice(c,1),d&&(f>=c&&f--,g>=c&&g--)}),this},has:function(a){return a?n.inArray(a,h)>-1:!(!h||!h.length)},empty:function(){return h=[],f=0,this},disable:function(){return h=i=b=void 0,this},disabled:function(){return!h},lock:function(){return i=void 0,b||k.disable(),this},locked:function(){return!i},fireWith:function(a,b){return!h||c&&!i||(b=b||[],b=[a,b.slice?b.slice():b],d?i.push(b):j(b)),this},fire:function(){return k.fireWith(this,arguments),this},fired:function(){return!!c}};return k},n.extend({Deferred:function(a){var b=[["resolve","done",n.Callbacks("once memory"),"resolved"],["reject","fail",n.Callbacks("once memory"),"rejected"],["notify","progress",n.Callbacks("memory")]],c="pending",d={state:function(){return c},always:function(){return e.done(arguments).fail(arguments),this},then:function(){var a=arguments;return n.Deferred(function(c){n.each(b,function(b,f){var g=n.isFunction(a[b])&&a[b];e[f[1]](function(){var a=g&&g.apply(this,arguments);a&&n.isFunction(a.promise)?a.promise().done(c.resolve).fail(c.reject).progress(c.notify):c[f[0]+"With"](this===d?c.promise():this,g?[a]:arguments)})}),a=null}).promise()},promise:function(a){return null!=a?n.extend(a,d):d}},e={};return d.pipe=d.then,n.each(b,function(a,f){var g=f[2],h=f[3];d[f[1]]=g.add,h&&g.add(function(){c=h},b[1^a][2].disable,b[2][2].lock),e[f[0]]=function(){return e[f[0]+"With"](this===e?d:this,arguments),this},e[f[0]+"With"]=g.fireWith}),d.promise(e),a&&a.call(e,e),e},when:function(a){var b=0,c=d.call(arguments),e=c.length,f=1!==e||a&&n.isFunction(a.promise)?e:0,g=1===f?a:n.Deferred(),h=function(a,b,c){return function(e){b[a]=this,c[a]=arguments.length>1?d.call(arguments):e,c===i?g.notifyWith(b,c):--f||g.resolveWith(b,c)}},i,j,k;if(e>1)for(i=new Array(e),j=new Array(e),k=new Array(e);e>b;b++)c[b]&&n.isFunction(c[b].promise)?c[b].promise().done(h(b,k,c)).fail(g.reject).progress(h(b,j,i)):--f;return f||g.resolveWith(k,c),g.promise()}});var H;n.fn.ready=function(a){return n.ready.promise().done(a),this},n.extend({isReady:!1,readyWait:1,holdReady:function(a){a?n.readyWait++:n.ready(!0)},ready:function(a){(a===!0?--n.readyWait:n.isReady)||(n.isReady=!0,a!==!0&&--n.readyWait>0||(H.resolveWith(l,[n]),n.fn.triggerHandler&&(n(l).triggerHandler("ready"),n(l).off("ready"))))}});function I(){l.removeEventListener("DOMContentLoaded",I,!1),a.removeEventListener("load",I,!1),n.ready()}n.ready.promise=function(b){return H||(H=n.Deferred(),"complete"===l.readyState?setTimeout(n.ready):(l.addEventListener("DOMContentLoaded",I,!1),a.addEventListener("load",I,!1))),H.promise(b)},n.ready.promise();var J=n.access=function(a,b,c,d,e,f,g){var h=0,i=a.length,j=null==c;if("object"===n.type(c)){e=!0;for(h in c)n.access(a,b,h,c[h],!0,f,g)}else if(void 0!==d&&(e=!0,n.isFunction(d)||(g=!0),j&&(g?(b.call(a,d),b=null):(j=b,b=function(a,b,c){return j.call(n(a),c)})),b))for(;i>h;h++)b(a[h],c,g?d:d.call(a[h],h,b(a[h],c)));return e?a:j?b.call(a):i?b(a[0],c):f};n.acceptData=function(a){return 1===a.nodeType||9===a.nodeType||!+a.nodeType};function K(){Object.defineProperty(this.cache={},0,{get:function(){return{}}}),this.expando=n.expando+K.uid++}K.uid=1,K.accepts=n.acceptData,K.prototype={key:function(a){if(!K.accepts(a))return 0;var b={},c=a[this.expando];if(!c){c=K.uid++;try{b[this.expando]={value:c},Object.defineProperties(a,b)}catch(d){b[this.expando]=c,n.extend(a,b)}}return this.cache[c]||(this.cache[c]={}),c},set:function(a,b,c){var d,e=this.key(a),f=this.cache[e];if("string"==typeof b)f[b]=c;else if(n.isEmptyObject(f))n.extend(this.cache[e],b);else for(d in b)f[d]=b[d];return f},get:function(a,b){var c=this.cache[this.key(a)];return void 0===b?c:c[b]},access:function(a,b,c){var d;return void 0===b||b&&"string"==typeof b&&void 0===c?(d=this.get(a,b),void 0!==d?d:this.get(a,n.camelCase(b))):(this.set(a,b,c),void 0!==c?c:b)},remove:function(a,b){var c,d,e,f=this.key(a),g=this.cache[f];if(void 0===b)this.cache[f]={};else{n.isArray(b)?d=b.concat(b.map(n.camelCase)):(e=n.camelCase(b),b in g?d=[b,e]:(d=e,d=d in g?[d]:d.match(E)||[])),c=d.length;while(c--)delete g[d[c]]}},hasData:function(a){return!n.isEmptyObject(this.cache[a[this.expando]]||{})},discard:function(a){a[this.expando]&&delete this.cache[a[this.expando]]}};var L=new K,M=new K,N=/^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,O=/([A-Z])/g;function P(a,b,c){var d;if(void 0===c&&1===a.nodeType)if(d="data-"+b.replace(O,"-$1").toLowerCase(),c=a.getAttribute(d),"string"==typeof c){try{c="true"===c?!0:"false"===c?!1:"null"===c?null:+c+""===c?+c:N.test(c)?n.parseJSON(c):c}catch(e){}M.set(a,b,c)}else c=void 0;return c}n.extend({hasData:function(a){return M.hasData(a)||L.hasData(a)},data:function(a,b,c){
return M.access(a,b,c)},removeData:function(a,b){M.remove(a,b)},_data:function(a,b,c){return L.access(a,b,c)},_removeData:function(a,b){L.remove(a,b)}}),n.fn.extend({data:function(a,b){var c,d,e,f=this[0],g=f&&f.attributes;if(void 0===a){if(this.length&&(e=M.get(f),1===f.nodeType&&!L.get(f,"hasDataAttrs"))){c=g.length;while(c--)g[c]&&(d=g[c].name,0===d.indexOf("data-")&&(d=n.camelCase(d.slice(5)),P(f,d,e[d])));L.set(f,"hasDataAttrs",!0)}return e}return"object"==typeof a?this.each(function(){M.set(this,a)}):J(this,function(b){var c,d=n.camelCase(a);if(f&&void 0===b){if(c=M.get(f,a),void 0!==c)return c;if(c=M.get(f,d),void 0!==c)return c;if(c=P(f,d,void 0),void 0!==c)return c}else this.each(function(){var c=M.get(this,d);M.set(this,d,b),-1!==a.indexOf("-")&&void 0!==c&&M.set(this,a,b)})},null,b,arguments.length>1,null,!0)},removeData:function(a){return this.each(function(){M.remove(this,a)})}}),n.extend({queue:function(a,b,c){var d;return a?(b=(b||"fx")+"queue",d=L.get(a,b),c&&(!d||n.isArray(c)?d=L.access(a,b,n.makeArray(c)):d.push(c)),d||[]):void 0},dequeue:function(a,b){b=b||"fx";var c=n.queue(a,b),d=c.length,e=c.shift(),f=n._queueHooks(a,b),g=function(){n.dequeue(a,b)};"inprogress"===e&&(e=c.shift(),d--),e&&("fx"===b&&c.unshift("inprogress"),delete f.stop,e.call(a,g,f)),!d&&f&&f.empty.fire()},_queueHooks:function(a,b){var c=b+"queueHooks";return L.get(a,c)||L.access(a,c,{empty:n.Callbacks("once memory").add(function(){L.remove(a,[b+"queue",c])})})}}),n.fn.extend({queue:function(a,b){var c=2;return"string"!=typeof a&&(b=a,a="fx",c--),arguments.length<c?n.queue(this[0],a):void 0===b?this:this.each(function(){var c=n.queue(this,a,b);n._queueHooks(this,a),"fx"===a&&"inprogress"!==c[0]&&n.dequeue(this,a)})},dequeue:function(a){return this.each(function(){n.dequeue(this,a)})},clearQueue:function(a){return this.queue(a||"fx",[])},promise:function(a,b){var c,d=1,e=n.Deferred(),f=this,g=this.length,h=function(){--d||e.resolveWith(f,[f])};"string"!=typeof a&&(b=a,a=void 0),a=a||"fx";while(g--)c=L.get(f[g],a+"queueHooks"),c&&c.empty&&(d++,c.empty.add(h));return h(),e.promise(b)}});var Q=/[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,R=["Top","Right","Bottom","Left"],S=function(a,b){return a=b||a,"none"===n.css(a,"display")||!n.contains(a.ownerDocument,a)},T=/^(?:checkbox|radio)$/i;!function(){var a=l.createDocumentFragment(),b=a.appendChild(l.createElement("div")),c=l.createElement("input");c.setAttribute("type","radio"),c.setAttribute("checked","checked"),c.setAttribute("name","t"),b.appendChild(c),k.checkClone=b.cloneNode(!0).cloneNode(!0).lastChild.checked,b.innerHTML="<textarea>x</textarea>",k.noCloneChecked=!!b.cloneNode(!0).lastChild.defaultValue}();var U="undefined";k.focusinBubbles="onfocusin"in a;var V=/^key/,W=/^(?:mouse|pointer|contextmenu)|click/,X=/^(?:focusinfocus|focusoutblur)$/,Y=/^([^.]*)(?:\.(.+)|)$/;function Z(){return!0}function $(){return!1}function _(){try{return l.activeElement}catch(a){}}n.event={global:{},add:function(a,b,c,d,e){var f,g,h,i,j,k,l,m,o,p,q,r=L.get(a);if(r){c.handler&&(f=c,c=f.handler,e=f.selector),c.guid||(c.guid=n.guid++),(i=r.events)||(i=r.events={}),(g=r.handle)||(g=r.handle=function(b){return typeof n!==U&&n.event.triggered!==b.type?n.event.dispatch.apply(a,arguments):void 0}),b=(b||"").match(E)||[""],j=b.length;while(j--)h=Y.exec(b[j])||[],o=q=h[1],p=(h[2]||"").split(".").sort(),o&&(l=n.event.special[o]||{},o=(e?l.delegateType:l.bindType)||o,l=n.event.special[o]||{},k=n.extend({type:o,origType:q,data:d,handler:c,guid:c.guid,selector:e,needsContext:e&&n.expr.match.needsContext.test(e),namespace:p.join(".")},f),(m=i[o])||(m=i[o]=[],m.delegateCount=0,l.setup&&l.setup.call(a,d,p,g)!==!1||a.addEventListener&&a.addEventListener(o,g,!1)),l.add&&(l.add.call(a,k),k.handler.guid||(k.handler.guid=c.guid)),e?m.splice(m.delegateCount++,0,k):m.push(k),n.event.global[o]=!0)}},remove:function(a,b,c,d,e){var f,g,h,i,j,k,l,m,o,p,q,r=L.hasData(a)&&L.get(a);if(r&&(i=r.events)){b=(b||"").match(E)||[""],j=b.length;while(j--)if(h=Y.exec(b[j])||[],o=q=h[1],p=(h[2]||"").split(".").sort(),o){l=n.event.special[o]||{},o=(d?l.delegateType:l.bindType)||o,m=i[o]||[],h=h[2]&&new RegExp("(^|\\.)"+p.join("\\.(?:.*\\.|)")+"(\\.|$)"),g=f=m.length;while(f--)k=m[f],!e&&q!==k.origType||c&&c.guid!==k.guid||h&&!h.test(k.namespace)||d&&d!==k.selector&&("**"!==d||!k.selector)||(m.splice(f,1),k.selector&&m.delegateCount--,l.remove&&l.remove.call(a,k));g&&!m.length&&(l.teardown&&l.teardown.call(a,p,r.handle)!==!1||n.removeEvent(a,o,r.handle),delete i[o])}else for(o in i)n.event.remove(a,o+b[j],c,d,!0);n.isEmptyObject(i)&&(delete r.handle,L.remove(a,"events"))}},trigger:function(b,c,d,e){var f,g,h,i,k,m,o,p=[d||l],q=j.call(b,"type")?b.type:b,r=j.call(b,"namespace")?b.namespace.split("."):[];if(g=h=d=d||l,3!==d.nodeType&&8!==d.nodeType&&!X.test(q+n.event.triggered)&&(q.indexOf(".")>=0&&(r=q.split("."),q=r.shift(),r.sort()),k=q.indexOf(":")<0&&"on"+q,b=b[n.expando]?b:new n.Event(q,"object"==typeof b&&b),b.isTrigger=e?2:3,b.namespace=r.join("."),b.namespace_re=b.namespace?new RegExp("(^|\\.)"+r.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,b.result=void 0,b.target||(b.target=d),c=null==c?[b]:n.makeArray(c,[b]),o=n.event.special[q]||{},e||!o.trigger||o.trigger.apply(d,c)!==!1)){if(!e&&!o.noBubble&&!n.isWindow(d)){for(i=o.delegateType||q,X.test(i+q)||(g=g.parentNode);g;g=g.parentNode)p.push(g),h=g;h===(d.ownerDocument||l)&&p.push(h.defaultView||h.parentWindow||a)}f=0;while((g=p[f++])&&!b.isPropagationStopped())b.type=f>1?i:o.bindType||q,m=(L.get(g,"events")||{})[b.type]&&L.get(g,"handle"),m&&m.apply(g,c),m=k&&g[k],m&&m.apply&&n.acceptData(g)&&(b.result=m.apply(g,c),b.result===!1&&b.preventDefault());return b.type=q,e||b.isDefaultPrevented()||o._default&&o._default.apply(p.pop(),c)!==!1||!n.acceptData(d)||k&&n.isFunction(d[q])&&!n.isWindow(d)&&(h=d[k],h&&(d[k]=null),n.event.triggered=q,d[q](),n.event.triggered=void 0,h&&(d[k]=h)),b.result}},dispatch:function(a){a=n.event.fix(a);var b,c,e,f,g,h=[],i=d.call(arguments),j=(L.get(this,"events")||{})[a.type]||[],k=n.event.special[a.type]||{};if(i[0]=a,a.delegateTarget=this,!k.preDispatch||k.preDispatch.call(this,a)!==!1){h=n.event.handlers.call(this,a,j),b=0;while((f=h[b++])&&!a.isPropagationStopped()){a.currentTarget=f.elem,c=0;while((g=f.handlers[c++])&&!a.isImmediatePropagationStopped())(!a.namespace_re||a.namespace_re.test(g.namespace))&&(a.handleObj=g,a.data=g.data,e=((n.event.special[g.origType]||{}).handle||g.handler).apply(f.elem,i),void 0!==e&&(a.result=e)===!1&&(a.preventDefault(),a.stopPropagation()))}return k.postDispatch&&k.postDispatch.call(this,a),a.result}},handlers:function(a,b){var c,d,e,f,g=[],h=b.delegateCount,i=a.target;if(h&&i.nodeType&&(!a.button||"click"!==a.type))for(;i!==this;i=i.parentNode||this)if(i.disabled!==!0||"click"!==a.type){for(d=[],c=0;h>c;c++)f=b[c],e=f.selector+" ",void 0===d[e]&&(d[e]=f.needsContext?n(e,this).index(i)>=0:n.find(e,this,null,[i]).length),d[e]&&d.push(f);d.length&&g.push({elem:i,handlers:d})}return h<b.length&&g.push({elem:this,handlers:b.slice(h)}),g},props:"altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),fixHooks:{},keyHooks:{props:"char charCode key keyCode".split(" "),filter:function(a,b){return null==a.which&&(a.which=null!=b.charCode?b.charCode:b.keyCode),a}},mouseHooks:{props:"button buttons clientX clientY offsetX offsetY pageX pageY screenX screenY toElement".split(" "),filter:function(a,b){var c,d,e,f=b.button;return null==a.pageX&&null!=b.clientX&&(c=a.target.ownerDocument||l,d=c.documentElement,e=c.body,a.pageX=b.clientX+(d&&d.scrollLeft||e&&e.scrollLeft||0)-(d&&d.clientLeft||e&&e.clientLeft||0),a.pageY=b.clientY+(d&&d.scrollTop||e&&e.scrollTop||0)-(d&&d.clientTop||e&&e.clientTop||0)),a.which||void 0===f||(a.which=1&f?1:2&f?3:4&f?2:0),a}},fix:function(a){if(a[n.expando])return a;var b,c,d,e=a.type,f=a,g=this.fixHooks[e];g||(this.fixHooks[e]=g=W.test(e)?this.mouseHooks:V.test(e)?this.keyHooks:{}),d=g.props?this.props.concat(g.props):this.props,a=new n.Event(f),b=d.length;while(b--)c=d[b],a[c]=f[c];return a.target||(a.target=l),3===a.target.nodeType&&(a.target=a.target.parentNode),g.filter?g.filter(a,f):a},special:{load:{noBubble:!0},focus:{trigger:function(){return this!==_()&&this.focus?(this.focus(),!1):void 0},delegateType:"focusin"},blur:{trigger:function(){return this===_()&&this.blur?(this.blur(),!1):void 0},delegateType:"focusout"},click:{trigger:function(){return"checkbox"===this.type&&this.click&&n.nodeName(this,"input")?(this.click(),!1):void 0},_default:function(a){return n.nodeName(a.target,"a")}},beforeunload:{postDispatch:function(a){void 0!==a.result&&a.originalEvent&&(a.originalEvent.returnValue=a.result)}}},simulate:function(a,b,c,d){var e=n.extend(new n.Event,c,{type:a,isSimulated:!0,originalEvent:{}});d?n.event.trigger(e,null,b):n.event.dispatch.call(b,e),e.isDefaultPrevented()&&c.preventDefault()}},n.removeEvent=function(a,b,c){a.removeEventListener&&a.removeEventListener(b,c,!1)},n.Event=function(a,b){return this instanceof n.Event?(a&&a.type?(this.originalEvent=a,this.type=a.type,this.isDefaultPrevented=a.defaultPrevented||void 0===a.defaultPrevented&&a.returnValue===!1?Z:$):this.type=a,b&&n.extend(this,b),this.timeStamp=a&&a.timeStamp||n.now(),void(this[n.expando]=!0)):new n.Event(a,b)},n.Event.prototype={isDefaultPrevented:$,isPropagationStopped:$,isImmediatePropagationStopped:$,preventDefault:function(){var a=this.originalEvent;this.isDefaultPrevented=Z,a&&a.preventDefault&&a.preventDefault()},stopPropagation:function(){var a=this.originalEvent;this.isPropagationStopped=Z,a&&a.stopPropagation&&a.stopPropagation()},stopImmediatePropagation:function(){var a=this.originalEvent;this.isImmediatePropagationStopped=Z,a&&a.stopImmediatePropagation&&a.stopImmediatePropagation(),this.stopPropagation()}},n.each({mouseenter:"mouseover",mouseleave:"mouseout",pointerenter:"pointerover",pointerleave:"pointerout"},function(a,b){n.event.special[a]={delegateType:b,bindType:b,handle:function(a){var c,d=this,e=a.relatedTarget,f=a.handleObj;return(!e||e!==d&&!n.contains(d,e))&&(a.type=f.origType,c=f.handler.apply(this,arguments),a.type=b),c}}}),k.focusinBubbles||n.each({focus:"focusin",blur:"focusout"},function(a,b){var c=function(a){n.event.simulate(b,a.target,n.event.fix(a),!0)};n.event.special[b]={setup:function(){var d=this.ownerDocument||this,e=L.access(d,b);e||d.addEventListener(a,c,!0),L.access(d,b,(e||0)+1)},teardown:function(){var d=this.ownerDocument||this,e=L.access(d,b)-1;e?L.access(d,b,e):(d.removeEventListener(a,c,!0),L.remove(d,b))}}}),n.fn.extend({on:function(a,b,c,d,e){var f,g;if("object"==typeof a){"string"!=typeof b&&(c=c||b,b=void 0);for(g in a)this.on(g,b,c,a[g],e);return this}if(null==c&&null==d?(d=b,c=b=void 0):null==d&&("string"==typeof b?(d=c,c=void 0):(d=c,c=b,b=void 0)),d===!1)d=$;else if(!d)return this;return 1===e&&(f=d,d=function(a){return n().off(a),f.apply(this,arguments)},d.guid=f.guid||(f.guid=n.guid++)),this.each(function(){n.event.add(this,a,d,c,b)})},one:function(a,b,c,d){return this.on(a,b,c,d,1)},off:function(a,b,c){var d,e;if(a&&a.preventDefault&&a.handleObj)return d=a.handleObj,n(a.delegateTarget).off(d.namespace?d.origType+"."+d.namespace:d.origType,d.selector,d.handler),this;if("object"==typeof a){for(e in a)this.off(e,b,a[e]);return this}return(b===!1||"function"==typeof b)&&(c=b,b=void 0),c===!1&&(c=$),this.each(function(){n.event.remove(this,a,c,b)})},trigger:function(a,b){return this.each(function(){n.event.trigger(a,b,this)})},triggerHandler:function(a,b){var c=this[0];return c?n.event.trigger(a,b,c,!0):void 0}});var aa=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,ba=/<([\w:]+)/,ca=/<|&#?\w+;/,da=/<(?:script|style|link)/i,ea=/checked\s*(?:[^=]|=\s*.checked.)/i,fa=/^$|\/(?:java|ecma)script/i,ga=/^true\/(.*)/,ha=/^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,ia={option:[1,"<select multiple='multiple'>","</select>"],thead:[1,"<table>","</table>"],col:[2,"<table><colgroup>","</colgroup></table>"],tr:[2,"<table><tbody>","</tbody></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],_default:[0,"",""]};ia.optgroup=ia.option,ia.tbody=ia.tfoot=ia.colgroup=ia.caption=ia.thead,ia.th=ia.td;function ja(a,b){return n.nodeName(a,"table")&&n.nodeName(11!==b.nodeType?b:b.firstChild,"tr")?a.getElementsByTagName("tbody")[0]||a.appendChild(a.ownerDocument.createElement("tbody")):a}function ka(a){return a.type=(null!==a.getAttribute("type"))+"/"+a.type,a}function la(a){var b=ga.exec(a.type);return b?a.type=b[1]:a.removeAttribute("type"),a}function ma(a,b){for(var c=0,d=a.length;d>c;c++)L.set(a[c],"globalEval",!b||L.get(b[c],"globalEval"))}function na(a,b){var c,d,e,f,g,h,i,j;if(1===b.nodeType){if(L.hasData(a)&&(f=L.access(a),g=L.set(b,f),j=f.events)){delete g.handle,g.events={};for(e in j)for(c=0,d=j[e].length;d>c;c++)n.event.add(b,e,j[e][c])}M.hasData(a)&&(h=M.access(a),i=n.extend({},h),M.set(b,i))}}function oa(a,b){var c=a.getElementsByTagName?a.getElementsByTagName(b||"*"):a.querySelectorAll?a.querySelectorAll(b||"*"):[];return void 0===b||b&&n.nodeName(a,b)?n.merge([a],c):c}function pa(a,b){var c=b.nodeName.toLowerCase();"input"===c&&T.test(a.type)?b.checked=a.checked:("input"===c||"textarea"===c)&&(b.defaultValue=a.defaultValue)}n.extend({clone:function(a,b,c){var d,e,f,g,h=a.cloneNode(!0),i=n.contains(a.ownerDocument,a);if(!(k.noCloneChecked||1!==a.nodeType&&11!==a.nodeType||n.isXMLDoc(a)))for(g=oa(h),f=oa(a),d=0,e=f.length;e>d;d++)pa(f[d],g[d]);if(b)if(c)for(f=f||oa(a),g=g||oa(h),d=0,e=f.length;e>d;d++)na(f[d],g[d]);else na(a,h);return g=oa(h,"script"),g.length>0&&ma(g,!i&&oa(a,"script")),h},buildFragment:function(a,b,c,d){for(var e,f,g,h,i,j,k=b.createDocumentFragment(),l=[],m=0,o=a.length;o>m;m++)if(e=a[m],e||0===e)if("object"===n.type(e))n.merge(l,e.nodeType?[e]:e);else if(ca.test(e)){f=f||k.appendChild(b.createElement("div")),g=(ba.exec(e)||["",""])[1].toLowerCase(),h=ia[g]||ia._default,f.innerHTML=h[1]+e.replace(aa,"<$1></$2>")+h[2],j=h[0];while(j--)f=f.lastChild;n.merge(l,f.childNodes),f=k.firstChild,f.textContent=""}else l.push(b.createTextNode(e));k.textContent="",m=0;while(e=l[m++])if((!d||-1===n.inArray(e,d))&&(i=n.contains(e.ownerDocument,e),f=oa(k.appendChild(e),"script"),i&&ma(f),c)){j=0;while(e=f[j++])fa.test(e.type||"")&&c.push(e)}return k},cleanData:function(a){for(var b,c,d,e,f=n.event.special,g=0;void 0!==(c=a[g]);g++){if(n.acceptData(c)&&(e=c[L.expando],e&&(b=L.cache[e]))){if(b.events)for(d in b.events)f[d]?n.event.remove(c,d):n.removeEvent(c,d,b.handle);L.cache[e]&&delete L.cache[e]}delete M.cache[c[M.expando]]}}}),n.fn.extend({text:function(a){return J(this,function(a){return void 0===a?n.text(this):this.empty().each(function(){(1===this.nodeType||11===this.nodeType||9===this.nodeType)&&(this.textContent=a)})},null,a,arguments.length)},append:function(){return this.domManip(arguments,function(a){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var b=ja(this,a);b.appendChild(a)}})},prepend:function(){return this.domManip(arguments,function(a){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var b=ja(this,a);b.insertBefore(a,b.firstChild)}})},before:function(){return this.domManip(arguments,function(a){this.parentNode&&this.parentNode.insertBefore(a,this)})},after:function(){return this.domManip(arguments,function(a){this.parentNode&&this.parentNode.insertBefore(a,this.nextSibling)})},remove:function(a,b){for(var c,d=a?n.filter(a,this):this,e=0;null!=(c=d[e]);e++)b||1!==c.nodeType||n.cleanData(oa(c)),c.parentNode&&(b&&n.contains(c.ownerDocument,c)&&ma(oa(c,"script")),c.parentNode.removeChild(c));return this},empty:function(){for(var a,b=0;null!=(a=this[b]);b++)1===a.nodeType&&(n.cleanData(oa(a,!1)),a.textContent="");return this},clone:function(a,b){return a=null==a?!1:a,b=null==b?a:b,this.map(function(){return n.clone(this,a,b)})},html:function(a){return J(this,function(a){var b=this[0]||{},c=0,d=this.length;if(void 0===a&&1===b.nodeType)return b.innerHTML;if("string"==typeof a&&!da.test(a)&&!ia[(ba.exec(a)||["",""])[1].toLowerCase()]){a=a.replace(aa,"<$1></$2>");try{for(;d>c;c++)b=this[c]||{},1===b.nodeType&&(n.cleanData(oa(b,!1)),b.innerHTML=a);b=0}catch(e){}}b&&this.empty().append(a)},null,a,arguments.length)},replaceWith:function(){var a=arguments[0];return this.domManip(arguments,function(b){a=this.parentNode,n.cleanData(oa(this)),a&&a.replaceChild(b,this)}),a&&(a.length||a.nodeType)?this:this.remove()},detach:function(a){return this.remove(a,!0)},domManip:function(a,b){a=e.apply([],a);var c,d,f,g,h,i,j=0,l=this.length,m=this,o=l-1,p=a[0],q=n.isFunction(p);if(q||l>1&&"string"==typeof p&&!k.checkClone&&ea.test(p))return this.each(function(c){var d=m.eq(c);q&&(a[0]=p.call(this,c,d.html())),d.domManip(a,b)});if(l&&(c=n.buildFragment(a,this[0].ownerDocument,!1,this),d=c.firstChild,1===c.childNodes.length&&(c=d),d)){for(f=n.map(oa(c,"script"),ka),g=f.length;l>j;j++)h=c,j!==o&&(h=n.clone(h,!0,!0),g&&n.merge(f,oa(h,"script"))),b.call(this[j],h,j);if(g)for(i=f[f.length-1].ownerDocument,n.map(f,la),j=0;g>j;j++)h=f[j],fa.test(h.type||"")&&!L.access(h,"globalEval")&&n.contains(i,h)&&(h.src?n._evalUrl&&n._evalUrl(h.src):n.globalEval(h.textContent.replace(ha,"")))}return this}}),n.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(a,b){n.fn[a]=function(a){for(var c,d=[],e=n(a),g=e.length-1,h=0;g>=h;h++)c=h===g?this:this.clone(!0),n(e[h])[b](c),f.apply(d,c.get());return this.pushStack(d)}});var qa,ra={};function sa(b,c){var d,e=n(c.createElement(b)).appendTo(c.body),f=a.getDefaultComputedStyle&&(d=a.getDefaultComputedStyle(e[0]))?d.display:n.css(e[0],"display");return e.detach(),f}function ta(a){var b=l,c=ra[a];return c||(c=sa(a,b),"none"!==c&&c||(qa=(qa||n("<iframe frameborder='0' width='0' height='0'/>")).appendTo(b.documentElement),b=qa[0].contentDocument,b.write(),b.close(),c=sa(a,b),qa.detach()),ra[a]=c),c}var ua=/^margin/,va=new RegExp("^("+Q+")(?!px)[a-z%]+$","i"),wa=function(b){return b.ownerDocument.defaultView.opener?b.ownerDocument.defaultView.getComputedStyle(b,null):a.getComputedStyle(b,null)};function xa(a,b,c){var d,e,f,g,h=a.style;return c=c||wa(a),c&&(g=c.getPropertyValue(b)||c[b]),c&&(""!==g||n.contains(a.ownerDocument,a)||(g=n.style(a,b)),va.test(g)&&ua.test(b)&&(d=h.width,e=h.minWidth,f=h.maxWidth,h.minWidth=h.maxWidth=h.width=g,g=c.width,h.width=d,h.minWidth=e,h.maxWidth=f)),void 0!==g?g+"":g}function ya(a,b){return{get:function(){return a()?void delete this.get:(this.get=b).apply(this,arguments)}}}!function(){var b,c,d=l.documentElement,e=l.createElement("div"),f=l.createElement("div");if(f.style){f.style.backgroundClip="content-box",f.cloneNode(!0).style.backgroundClip="",k.clearCloneStyle="content-box"===f.style.backgroundClip,e.style.cssText="border:0;width:0;height:0;top:0;left:-9999px;margin-top:1px;position:absolute",e.appendChild(f);function g(){f.style.cssText="-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:block;margin-top:1%;top:1%;border:1px;padding:1px;width:4px;position:absolute",f.innerHTML="",d.appendChild(e);var g=a.getComputedStyle(f,null);b="1%"!==g.top,c="4px"===g.width,d.removeChild(e)}a.getComputedStyle&&n.extend(k,{pixelPosition:function(){return g(),b},boxSizingReliable:function(){return null==c&&g(),c},reliableMarginRight:function(){var b,c=f.appendChild(l.createElement("div"));return c.style.cssText=f.style.cssText="-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:0",c.style.marginRight=c.style.width="0",f.style.width="1px",d.appendChild(e),b=!parseFloat(a.getComputedStyle(c,null).marginRight),d.removeChild(e),f.removeChild(c),b}})}}(),n.swap=function(a,b,c,d){var e,f,g={};for(f in b)g[f]=a.style[f],a.style[f]=b[f];e=c.apply(a,d||[]);for(f in b)a.style[f]=g[f];return e};var za=/^(none|table(?!-c[ea]).+)/,Aa=new RegExp("^("+Q+")(.*)$","i"),Ba=new RegExp("^([+-])=("+Q+")","i"),Ca={position:"absolute",visibility:"hidden",display:"block"},Da={letterSpacing:"0",fontWeight:"400"},Ea=["Webkit","O","Moz","ms"];function Fa(a,b){if(b in a)return b;var c=b[0].toUpperCase()+b.slice(1),d=b,e=Ea.length;while(e--)if(b=Ea[e]+c,b in a)return b;return d}function Ga(a,b,c){var d=Aa.exec(b);return d?Math.max(0,d[1]-(c||0))+(d[2]||"px"):b}function Ha(a,b,c,d,e){for(var f=c===(d?"border":"content")?4:"width"===b?1:0,g=0;4>f;f+=2)"margin"===c&&(g+=n.css(a,c+R[f],!0,e)),d?("content"===c&&(g-=n.css(a,"padding"+R[f],!0,e)),"margin"!==c&&(g-=n.css(a,"border"+R[f]+"Width",!0,e))):(g+=n.css(a,"padding"+R[f],!0,e),"padding"!==c&&(g+=n.css(a,"border"+R[f]+"Width",!0,e)));return g}function Ia(a,b,c){var d=!0,e="width"===b?a.offsetWidth:a.offsetHeight,f=wa(a),g="border-box"===n.css(a,"boxSizing",!1,f);if(0>=e||null==e){if(e=xa(a,b,f),(0>e||null==e)&&(e=a.style[b]),va.test(e))return e;d=g&&(k.boxSizingReliable()||e===a.style[b]),e=parseFloat(e)||0}return e+Ha(a,b,c||(g?"border":"content"),d,f)+"px"}function Ja(a,b){for(var c,d,e,f=[],g=0,h=a.length;h>g;g++)d=a[g],d.style&&(f[g]=L.get(d,"olddisplay"),c=d.style.display,b?(f[g]||"none"!==c||(d.style.display=""),""===d.style.display&&S(d)&&(f[g]=L.access(d,"olddisplay",ta(d.nodeName)))):(e=S(d),"none"===c&&e||L.set(d,"olddisplay",e?c:n.css(d,"display"))));for(g=0;h>g;g++)d=a[g],d.style&&(b&&"none"!==d.style.display&&""!==d.style.display||(d.style.display=b?f[g]||"":"none"));return a}n.extend({cssHooks:{opacity:{get:function(a,b){if(b){var c=xa(a,"opacity");return""===c?"1":c}}}},cssNumber:{columnCount:!0,fillOpacity:!0,flexGrow:!0,flexShrink:!0,fontWeight:!0,lineHeight:!0,opacity:!0,order:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{"float":"cssFloat"},style:function(a,b,c,d){if(a&&3!==a.nodeType&&8!==a.nodeType&&a.style){var e,f,g,h=n.camelCase(b),i=a.style;return b=n.cssProps[h]||(n.cssProps[h]=Fa(i,h)),g=n.cssHooks[b]||n.cssHooks[h],void 0===c?g&&"get"in g&&void 0!==(e=g.get(a,!1,d))?e:i[b]:(f=typeof c,"string"===f&&(e=Ba.exec(c))&&(c=(e[1]+1)*e[2]+parseFloat(n.css(a,b)),f="number"),null!=c&&c===c&&("number"!==f||n.cssNumber[h]||(c+="px"),k.clearCloneStyle||""!==c||0!==b.indexOf("background")||(i[b]="inherit"),g&&"set"in g&&void 0===(c=g.set(a,c,d))||(i[b]=c)),void 0)}},css:function(a,b,c,d){var e,f,g,h=n.camelCase(b);return b=n.cssProps[h]||(n.cssProps[h]=Fa(a.style,h)),g=n.cssHooks[b]||n.cssHooks[h],g&&"get"in g&&(e=g.get(a,!0,c)),void 0===e&&(e=xa(a,b,d)),"normal"===e&&b in Da&&(e=Da[b]),""===c||c?(f=parseFloat(e),c===!0||n.isNumeric(f)?f||0:e):e}}),n.each(["height","width"],function(a,b){n.cssHooks[b]={get:function(a,c,d){return c?za.test(n.css(a,"display"))&&0===a.offsetWidth?n.swap(a,Ca,function(){return Ia(a,b,d)}):Ia(a,b,d):void 0},set:function(a,c,d){var e=d&&wa(a);return Ga(a,c,d?Ha(a,b,d,"border-box"===n.css(a,"boxSizing",!1,e),e):0)}}}),n.cssHooks.marginRight=ya(k.reliableMarginRight,function(a,b){return b?n.swap(a,{display:"inline-block"},xa,[a,"marginRight"]):void 0}),n.each({margin:"",padding:"",border:"Width"},function(a,b){n.cssHooks[a+b]={expand:function(c){for(var d=0,e={},f="string"==typeof c?c.split(" "):[c];4>d;d++)e[a+R[d]+b]=f[d]||f[d-2]||f[0];return e}},ua.test(a)||(n.cssHooks[a+b].set=Ga)}),n.fn.extend({css:function(a,b){return J(this,function(a,b,c){var d,e,f={},g=0;if(n.isArray(b)){for(d=wa(a),e=b.length;e>g;g++)f[b[g]]=n.css(a,b[g],!1,d);return f}return void 0!==c?n.style(a,b,c):n.css(a,b)},a,b,arguments.length>1)},show:function(){return Ja(this,!0)},hide:function(){return Ja(this)},toggle:function(a){return"boolean"==typeof a?a?this.show():this.hide():this.each(function(){S(this)?n(this).show():n(this).hide()})}});function Ka(a,b,c,d,e){return new Ka.prototype.init(a,b,c,d,e)}n.Tween=Ka,Ka.prototype={constructor:Ka,init:function(a,b,c,d,e,f){this.elem=a,this.prop=c,this.easing=e||"swing",this.options=b,this.start=this.now=this.cur(),this.end=d,this.unit=f||(n.cssNumber[c]?"":"px")},cur:function(){var a=Ka.propHooks[this.prop];return a&&a.get?a.get(this):Ka.propHooks._default.get(this)},run:function(a){var b,c=Ka.propHooks[this.prop];return this.options.duration?this.pos=b=n.easing[this.easing](a,this.options.duration*a,0,1,this.options.duration):this.pos=b=a,this.now=(this.end-this.start)*b+this.start,this.options.step&&this.options.step.call(this.elem,this.now,this),c&&c.set?c.set(this):Ka.propHooks._default.set(this),this}},Ka.prototype.init.prototype=Ka.prototype,Ka.propHooks={_default:{get:function(a){var b;return null==a.elem[a.prop]||a.elem.style&&null!=a.elem.style[a.prop]?(b=n.css(a.elem,a.prop,""),b&&"auto"!==b?b:0):a.elem[a.prop]},set:function(a){n.fx.step[a.prop]?n.fx.step[a.prop](a):a.elem.style&&(null!=a.elem.style[n.cssProps[a.prop]]||n.cssHooks[a.prop])?n.style(a.elem,a.prop,a.now+a.unit):a.elem[a.prop]=a.now}}},Ka.propHooks.scrollTop=Ka.propHooks.scrollLeft={set:function(a){a.elem.nodeType&&a.elem.parentNode&&(a.elem[a.prop]=a.now)}},n.easing={linear:function(a){return a},swing:function(a){return.5-Math.cos(a*Math.PI)/2}},n.fx=Ka.prototype.init,n.fx.step={};var La,Ma,Na=/^(?:toggle|show|hide)$/,Oa=new RegExp("^(?:([+-])=|)("+Q+")([a-z%]*)$","i"),Pa=/queueHooks$/,Qa=[Va],Ra={"*":[function(a,b){var c=this.createTween(a,b),d=c.cur(),e=Oa.exec(b),f=e&&e[3]||(n.cssNumber[a]?"":"px"),g=(n.cssNumber[a]||"px"!==f&&+d)&&Oa.exec(n.css(c.elem,a)),h=1,i=20;if(g&&g[3]!==f){f=f||g[3],e=e||[],g=+d||1;do h=h||".5",g/=h,n.style(c.elem,a,g+f);while(h!==(h=c.cur()/d)&&1!==h&&--i)}return e&&(g=c.start=+g||+d||0,c.unit=f,c.end=e[1]?g+(e[1]+1)*e[2]:+e[2]),c}]};function Sa(){return setTimeout(function(){La=void 0}),La=n.now()}function Ta(a,b){var c,d=0,e={height:a};for(b=b?1:0;4>d;d+=2-b)c=R[d],e["margin"+c]=e["padding"+c]=a;return b&&(e.opacity=e.width=a),e}function Ua(a,b,c){for(var d,e=(Ra[b]||[]).concat(Ra["*"]),f=0,g=e.length;g>f;f++)if(d=e[f].call(c,b,a))return d}function Va(a,b,c){var d,e,f,g,h,i,j,k,l=this,m={},o=a.style,p=a.nodeType&&S(a),q=L.get(a,"fxshow");c.queue||(h=n._queueHooks(a,"fx"),null==h.unqueued&&(h.unqueued=0,i=h.empty.fire,h.empty.fire=function(){h.unqueued||i()}),h.unqueued++,l.always(function(){l.always(function(){h.unqueued--,n.queue(a,"fx").length||h.empty.fire()})})),1===a.nodeType&&("height"in b||"width"in b)&&(c.overflow=[o.overflow,o.overflowX,o.overflowY],j=n.css(a,"display"),k="none"===j?L.get(a,"olddisplay")||ta(a.nodeName):j,"inline"===k&&"none"===n.css(a,"float")&&(o.display="inline-block")),c.overflow&&(o.overflow="hidden",l.always(function(){o.overflow=c.overflow[0],o.overflowX=c.overflow[1],o.overflowY=c.overflow[2]}));for(d in b)if(e=b[d],Na.exec(e)){if(delete b[d],f=f||"toggle"===e,e===(p?"hide":"show")){if("show"!==e||!q||void 0===q[d])continue;p=!0}m[d]=q&&q[d]||n.style(a,d)}else j=void 0;if(n.isEmptyObject(m))"inline"===("none"===j?ta(a.nodeName):j)&&(o.display=j);else{q?"hidden"in q&&(p=q.hidden):q=L.access(a,"fxshow",{}),f&&(q.hidden=!p),p?n(a).show():l.done(function(){n(a).hide()}),l.done(function(){var b;L.remove(a,"fxshow");for(b in m)n.style(a,b,m[b])});for(d in m)g=Ua(p?q[d]:0,d,l),d in q||(q[d]=g.start,p&&(g.end=g.start,g.start="width"===d||"height"===d?1:0))}}function Wa(a,b){var c,d,e,f,g;for(c in a)if(d=n.camelCase(c),e=b[d],f=a[c],n.isArray(f)&&(e=f[1],f=a[c]=f[0]),c!==d&&(a[d]=f,delete a[c]),g=n.cssHooks[d],g&&"expand"in g){f=g.expand(f),delete a[d];for(c in f)c in a||(a[c]=f[c],b[c]=e)}else b[d]=e}function Xa(a,b,c){var d,e,f=0,g=Qa.length,h=n.Deferred().always(function(){delete i.elem}),i=function(){if(e)return!1;for(var b=La||Sa(),c=Math.max(0,j.startTime+j.duration-b),d=c/j.duration||0,f=1-d,g=0,i=j.tweens.length;i>g;g++)j.tweens[g].run(f);return h.notifyWith(a,[j,f,c]),1>f&&i?c:(h.resolveWith(a,[j]),!1)},j=h.promise({elem:a,props:n.extend({},b),opts:n.extend(!0,{specialEasing:{}},c),originalProperties:b,originalOptions:c,startTime:La||Sa(),duration:c.duration,tweens:[],createTween:function(b,c){var d=n.Tween(a,j.opts,b,c,j.opts.specialEasing[b]||j.opts.easing);return j.tweens.push(d),d},stop:function(b){var c=0,d=b?j.tweens.length:0;if(e)return this;for(e=!0;d>c;c++)j.tweens[c].run(1);return b?h.resolveWith(a,[j,b]):h.rejectWith(a,[j,b]),this}}),k=j.props;for(Wa(k,j.opts.specialEasing);g>f;f++)if(d=Qa[f].call(j,a,k,j.opts))return d;return n.map(k,Ua,j),n.isFunction(j.opts.start)&&j.opts.start.call(a,j),n.fx.timer(n.extend(i,{elem:a,anim:j,queue:j.opts.queue})),j.progress(j.opts.progress).done(j.opts.done,j.opts.complete).fail(j.opts.fail).always(j.opts.always)}n.Animation=n.extend(Xa,{tweener:function(a,b){n.isFunction(a)?(b=a,a=["*"]):a=a.split(" ");for(var c,d=0,e=a.length;e>d;d++)c=a[d],Ra[c]=Ra[c]||[],Ra[c].unshift(b)},prefilter:function(a,b){b?Qa.unshift(a):Qa.push(a)}}),n.speed=function(a,b,c){var d=a&&"object"==typeof a?n.extend({},a):{complete:c||!c&&b||n.isFunction(a)&&a,duration:a,easing:c&&b||b&&!n.isFunction(b)&&b};return d.duration=n.fx.off?0:"number"==typeof d.duration?d.duration:d.duration in n.fx.speeds?n.fx.speeds[d.duration]:n.fx.speeds._default,(null==d.queue||d.queue===!0)&&(d.queue="fx"),d.old=d.complete,d.complete=function(){n.isFunction(d.old)&&d.old.call(this),d.queue&&n.dequeue(this,d.queue)},d},n.fn.extend({fadeTo:function(a,b,c,d){return this.filter(S).css("opacity",0).show().end().animate({opacity:b},a,c,d)},animate:function(a,b,c,d){var e=n.isEmptyObject(a),f=n.speed(b,c,d),g=function(){var b=Xa(this,n.extend({},a),f);(e||L.get(this,"finish"))&&b.stop(!0)};return g.finish=g,e||f.queue===!1?this.each(g):this.queue(f.queue,g)},stop:function(a,b,c){var d=function(a){var b=a.stop;delete a.stop,b(c)};return"string"!=typeof a&&(c=b,b=a,a=void 0),b&&a!==!1&&this.queue(a||"fx",[]),this.each(function(){var b=!0,e=null!=a&&a+"queueHooks",f=n.timers,g=L.get(this);if(e)g[e]&&g[e].stop&&d(g[e]);else for(e in g)g[e]&&g[e].stop&&Pa.test(e)&&d(g[e]);for(e=f.length;e--;)f[e].elem!==this||null!=a&&f[e].queue!==a||(f[e].anim.stop(c),b=!1,f.splice(e,1));(b||!c)&&n.dequeue(this,a)})},finish:function(a){return a!==!1&&(a=a||"fx"),this.each(function(){var b,c=L.get(this),d=c[a+"queue"],e=c[a+"queueHooks"],f=n.timers,g=d?d.length:0;for(c.finish=!0,n.queue(this,a,[]),e&&e.stop&&e.stop.call(this,!0),b=f.length;b--;)f[b].elem===this&&f[b].queue===a&&(f[b].anim.stop(!0),f.splice(b,1));for(b=0;g>b;b++)d[b]&&d[b].finish&&d[b].finish.call(this);delete c.finish})}}),n.each(["toggle","show","hide"],function(a,b){var c=n.fn[b];n.fn[b]=function(a,d,e){return null==a||"boolean"==typeof a?c.apply(this,arguments):this.animate(Ta(b,!0),a,d,e)}}),n.each({slideDown:Ta("show"),slideUp:Ta("hide"),slideToggle:Ta("toggle"),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(a,b){n.fn[a]=function(a,c,d){return this.animate(b,a,c,d)}}),n.timers=[],n.fx.tick=function(){var a,b=0,c=n.timers;for(La=n.now();b<c.length;b++)a=c[b],a()||c[b]!==a||c.splice(b--,1);c.length||n.fx.stop(),La=void 0},n.fx.timer=function(a){n.timers.push(a),a()?n.fx.start():n.timers.pop()},n.fx.interval=13,n.fx.start=function(){Ma||(Ma=setInterval(n.fx.tick,n.fx.interval))},n.fx.stop=function(){clearInterval(Ma),Ma=null},n.fx.speeds={slow:600,fast:200,_default:400},n.fn.delay=function(a,b){return a=n.fx?n.fx.speeds[a]||a:a,b=b||"fx",this.queue(b,function(b,c){var d=setTimeout(b,a);c.stop=function(){clearTimeout(d)}})},function(){var a=l.createElement("input"),b=l.createElement("select"),c=b.appendChild(l.createElement("option"));a.type="checkbox",k.checkOn=""!==a.value,k.optSelected=c.selected,b.disabled=!0,k.optDisabled=!c.disabled,a=l.createElement("input"),a.value="t",a.type="radio",k.radioValue="t"===a.value}();var Ya,Za,$a=n.expr.attrHandle;n.fn.extend({attr:function(a,b){return J(this,n.attr,a,b,arguments.length>1)},removeAttr:function(a){return this.each(function(){n.removeAttr(this,a)})}}),n.extend({attr:function(a,b,c){var d,e,f=a.nodeType;if(a&&3!==f&&8!==f&&2!==f)return typeof a.getAttribute===U?n.prop(a,b,c):(1===f&&n.isXMLDoc(a)||(b=b.toLowerCase(),d=n.attrHooks[b]||(n.expr.match.bool.test(b)?Za:Ya)),
void 0===c?d&&"get"in d&&null!==(e=d.get(a,b))?e:(e=n.find.attr(a,b),null==e?void 0:e):null!==c?d&&"set"in d&&void 0!==(e=d.set(a,c,b))?e:(a.setAttribute(b,c+""),c):void n.removeAttr(a,b))},removeAttr:function(a,b){var c,d,e=0,f=b&&b.match(E);if(f&&1===a.nodeType)while(c=f[e++])d=n.propFix[c]||c,n.expr.match.bool.test(c)&&(a[d]=!1),a.removeAttribute(c)},attrHooks:{type:{set:function(a,b){if(!k.radioValue&&"radio"===b&&n.nodeName(a,"input")){var c=a.value;return a.setAttribute("type",b),c&&(a.value=c),b}}}}}),Za={set:function(a,b,c){return b===!1?n.removeAttr(a,c):a.setAttribute(c,c),c}},n.each(n.expr.match.bool.source.match(/\w+/g),function(a,b){var c=$a[b]||n.find.attr;$a[b]=function(a,b,d){var e,f;return d||(f=$a[b],$a[b]=e,e=null!=c(a,b,d)?b.toLowerCase():null,$a[b]=f),e}});var _a=/^(?:input|select|textarea|button)$/i;n.fn.extend({prop:function(a,b){return J(this,n.prop,a,b,arguments.length>1)},removeProp:function(a){return this.each(function(){delete this[n.propFix[a]||a]})}}),n.extend({propFix:{"for":"htmlFor","class":"className"},prop:function(a,b,c){var d,e,f,g=a.nodeType;if(a&&3!==g&&8!==g&&2!==g)return f=1!==g||!n.isXMLDoc(a),f&&(b=n.propFix[b]||b,e=n.propHooks[b]),void 0!==c?e&&"set"in e&&void 0!==(d=e.set(a,c,b))?d:a[b]=c:e&&"get"in e&&null!==(d=e.get(a,b))?d:a[b]},propHooks:{tabIndex:{get:function(a){return a.hasAttribute("tabindex")||_a.test(a.nodeName)||a.href?a.tabIndex:-1}}}}),k.optSelected||(n.propHooks.selected={get:function(a){var b=a.parentNode;return b&&b.parentNode&&b.parentNode.selectedIndex,null}}),n.each(["tabIndex","readOnly","maxLength","cellSpacing","cellPadding","rowSpan","colSpan","useMap","frameBorder","contentEditable"],function(){n.propFix[this.toLowerCase()]=this});var ab=/[\t\r\n\f]/g;n.fn.extend({addClass:function(a){var b,c,d,e,f,g,h="string"==typeof a&&a,i=0,j=this.length;if(n.isFunction(a))return this.each(function(b){n(this).addClass(a.call(this,b,this.className))});if(h)for(b=(a||"").match(E)||[];j>i;i++)if(c=this[i],d=1===c.nodeType&&(c.className?(" "+c.className+" ").replace(ab," "):" ")){f=0;while(e=b[f++])d.indexOf(" "+e+" ")<0&&(d+=e+" ");g=n.trim(d),c.className!==g&&(c.className=g)}return this},removeClass:function(a){var b,c,d,e,f,g,h=0===arguments.length||"string"==typeof a&&a,i=0,j=this.length;if(n.isFunction(a))return this.each(function(b){n(this).removeClass(a.call(this,b,this.className))});if(h)for(b=(a||"").match(E)||[];j>i;i++)if(c=this[i],d=1===c.nodeType&&(c.className?(" "+c.className+" ").replace(ab," "):"")){f=0;while(e=b[f++])while(d.indexOf(" "+e+" ")>=0)d=d.replace(" "+e+" "," ");g=a?n.trim(d):"",c.className!==g&&(c.className=g)}return this},toggleClass:function(a,b){var c=typeof a;return"boolean"==typeof b&&"string"===c?b?this.addClass(a):this.removeClass(a):this.each(n.isFunction(a)?function(c){n(this).toggleClass(a.call(this,c,this.className,b),b)}:function(){if("string"===c){var b,d=0,e=n(this),f=a.match(E)||[];while(b=f[d++])e.hasClass(b)?e.removeClass(b):e.addClass(b)}else(c===U||"boolean"===c)&&(this.className&&L.set(this,"__className__",this.className),this.className=this.className||a===!1?"":L.get(this,"__className__")||"")})},hasClass:function(a){for(var b=" "+a+" ",c=0,d=this.length;d>c;c++)if(1===this[c].nodeType&&(" "+this[c].className+" ").replace(ab," ").indexOf(b)>=0)return!0;return!1}});var bb=/\r/g;n.fn.extend({val:function(a){var b,c,d,e=this[0];{if(arguments.length)return d=n.isFunction(a),this.each(function(c){var e;1===this.nodeType&&(e=d?a.call(this,c,n(this).val()):a,null==e?e="":"number"==typeof e?e+="":n.isArray(e)&&(e=n.map(e,function(a){return null==a?"":a+""})),b=n.valHooks[this.type]||n.valHooks[this.nodeName.toLowerCase()],b&&"set"in b&&void 0!==b.set(this,e,"value")||(this.value=e))});if(e)return b=n.valHooks[e.type]||n.valHooks[e.nodeName.toLowerCase()],b&&"get"in b&&void 0!==(c=b.get(e,"value"))?c:(c=e.value,"string"==typeof c?c.replace(bb,""):null==c?"":c)}}}),n.extend({valHooks:{option:{get:function(a){var b=n.find.attr(a,"value");return null!=b?b:n.trim(n.text(a))}},select:{get:function(a){for(var b,c,d=a.options,e=a.selectedIndex,f="select-one"===a.type||0>e,g=f?null:[],h=f?e+1:d.length,i=0>e?h:f?e:0;h>i;i++)if(c=d[i],!(!c.selected&&i!==e||(k.optDisabled?c.disabled:null!==c.getAttribute("disabled"))||c.parentNode.disabled&&n.nodeName(c.parentNode,"optgroup"))){if(b=n(c).val(),f)return b;g.push(b)}return g},set:function(a,b){var c,d,e=a.options,f=n.makeArray(b),g=e.length;while(g--)d=e[g],(d.selected=n.inArray(d.value,f)>=0)&&(c=!0);return c||(a.selectedIndex=-1),f}}}}),n.each(["radio","checkbox"],function(){n.valHooks[this]={set:function(a,b){return n.isArray(b)?a.checked=n.inArray(n(a).val(),b)>=0:void 0}},k.checkOn||(n.valHooks[this].get=function(a){return null===a.getAttribute("value")?"on":a.value})}),n.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),function(a,b){n.fn[b]=function(a,c){return arguments.length>0?this.on(b,null,a,c):this.trigger(b)}}),n.fn.extend({hover:function(a,b){return this.mouseenter(a).mouseleave(b||a)},bind:function(a,b,c){return this.on(a,null,b,c)},unbind:function(a,b){return this.off(a,null,b)},delegate:function(a,b,c,d){return this.on(b,a,c,d)},undelegate:function(a,b,c){return 1===arguments.length?this.off(a,"**"):this.off(b,a||"**",c)}});var cb=n.now(),db=/\?/;n.parseJSON=function(a){return JSON.parse(a+"")},n.parseXML=function(a){var b,c;if(!a||"string"!=typeof a)return null;try{c=new DOMParser,b=c.parseFromString(a,"text/xml")}catch(d){b=void 0}return(!b||b.getElementsByTagName("parsererror").length)&&n.error("Invalid XML: "+a),b};var eb=/#.*$/,fb=/([?&])_=[^&]*/,gb=/^(.*?):[ \t]*([^\r\n]*)$/gm,hb=/^(?:about|app|app-storage|.+-extension|file|res|widget):$/,ib=/^(?:GET|HEAD)$/,jb=/^\/\//,kb=/^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/,lb={},mb={},nb="*/".concat("*"),ob=a.location.href,pb=kb.exec(ob.toLowerCase())||[];function qb(a){return function(b,c){"string"!=typeof b&&(c=b,b="*");var d,e=0,f=b.toLowerCase().match(E)||[];if(n.isFunction(c))while(d=f[e++])"+"===d[0]?(d=d.slice(1)||"*",(a[d]=a[d]||[]).unshift(c)):(a[d]=a[d]||[]).push(c)}}function rb(a,b,c,d){var e={},f=a===mb;function g(h){var i;return e[h]=!0,n.each(a[h]||[],function(a,h){var j=h(b,c,d);return"string"!=typeof j||f||e[j]?f?!(i=j):void 0:(b.dataTypes.unshift(j),g(j),!1)}),i}return g(b.dataTypes[0])||!e["*"]&&g("*")}function sb(a,b){var c,d,e=n.ajaxSettings.flatOptions||{};for(c in b)void 0!==b[c]&&((e[c]?a:d||(d={}))[c]=b[c]);return d&&n.extend(!0,a,d),a}function tb(a,b,c){var d,e,f,g,h=a.contents,i=a.dataTypes;while("*"===i[0])i.shift(),void 0===d&&(d=a.mimeType||b.getResponseHeader("Content-Type"));if(d)for(e in h)if(h[e]&&h[e].test(d)){i.unshift(e);break}if(i[0]in c)f=i[0];else{for(e in c){if(!i[0]||a.converters[e+" "+i[0]]){f=e;break}g||(g=e)}f=f||g}return f?(f!==i[0]&&i.unshift(f),c[f]):void 0}function ub(a,b,c,d){var e,f,g,h,i,j={},k=a.dataTypes.slice();if(k[1])for(g in a.converters)j[g.toLowerCase()]=a.converters[g];f=k.shift();while(f)if(a.responseFields[f]&&(c[a.responseFields[f]]=b),!i&&d&&a.dataFilter&&(b=a.dataFilter(b,a.dataType)),i=f,f=k.shift())if("*"===f)f=i;else if("*"!==i&&i!==f){if(g=j[i+" "+f]||j["* "+f],!g)for(e in j)if(h=e.split(" "),h[1]===f&&(g=j[i+" "+h[0]]||j["* "+h[0]])){g===!0?g=j[e]:j[e]!==!0&&(f=h[0],k.unshift(h[1]));break}if(g!==!0)if(g&&a["throws"])b=g(b);else try{b=g(b)}catch(l){return{state:"parsererror",error:g?l:"No conversion from "+i+" to "+f}}}return{state:"success",data:b}}n.extend({active:0,lastModified:{},etag:{},ajaxSettings:{url:ob,type:"GET",isLocal:hb.test(pb[1]),global:!0,processData:!0,async:!0,contentType:"application/x-www-form-urlencoded; charset=UTF-8",accepts:{"*":nb,text:"text/plain",html:"text/html",xml:"application/xml, text/xml",json:"application/json, text/javascript"},contents:{xml:/xml/,html:/html/,json:/json/},responseFields:{xml:"responseXML",text:"responseText",json:"responseJSON"},converters:{"* text":String,"text html":!0,"text json":n.parseJSON,"text xml":n.parseXML},flatOptions:{url:!0,context:!0}},ajaxSetup:function(a,b){return b?sb(sb(a,n.ajaxSettings),b):sb(n.ajaxSettings,a)},ajaxPrefilter:qb(lb),ajaxTransport:qb(mb),ajax:function(a,b){"object"==typeof a&&(b=a,a=void 0),b=b||{};var c,d,e,f,g,h,i,j,k=n.ajaxSetup({},b),l=k.context||k,m=k.context&&(l.nodeType||l.jquery)?n(l):n.event,o=n.Deferred(),p=n.Callbacks("once memory"),q=k.statusCode||{},r={},s={},t=0,u="canceled",v={readyState:0,getResponseHeader:function(a){var b;if(2===t){if(!f){f={};while(b=gb.exec(e))f[b[1].toLowerCase()]=b[2]}b=f[a.toLowerCase()]}return null==b?null:b},getAllResponseHeaders:function(){return 2===t?e:null},setRequestHeader:function(a,b){var c=a.toLowerCase();return t||(a=s[c]=s[c]||a,r[a]=b),this},overrideMimeType:function(a){return t||(k.mimeType=a),this},statusCode:function(a){var b;if(a)if(2>t)for(b in a)q[b]=[q[b],a[b]];else v.always(a[v.status]);return this},abort:function(a){var b=a||u;return c&&c.abort(b),x(0,b),this}};if(o.promise(v).complete=p.add,v.success=v.done,v.error=v.fail,k.url=((a||k.url||ob)+"").replace(eb,"").replace(jb,pb[1]+"//"),k.type=b.method||b.type||k.method||k.type,k.dataTypes=n.trim(k.dataType||"*").toLowerCase().match(E)||[""],null==k.crossDomain&&(h=kb.exec(k.url.toLowerCase()),k.crossDomain=!(!h||h[1]===pb[1]&&h[2]===pb[2]&&(h[3]||("http:"===h[1]?"80":"443"))===(pb[3]||("http:"===pb[1]?"80":"443")))),k.data&&k.processData&&"string"!=typeof k.data&&(k.data=n.param(k.data,k.traditional)),rb(lb,k,b,v),2===t)return v;i=n.event&&k.global,i&&0===n.active++&&n.event.trigger("ajaxStart"),k.type=k.type.toUpperCase(),k.hasContent=!ib.test(k.type),d=k.url,k.hasContent||(k.data&&(d=k.url+=(db.test(d)?"&":"?")+k.data,delete k.data),k.cache===!1&&(k.url=fb.test(d)?d.replace(fb,"$1_="+cb++):d+(db.test(d)?"&":"?")+"_="+cb++)),k.ifModified&&(n.lastModified[d]&&v.setRequestHeader("If-Modified-Since",n.lastModified[d]),n.etag[d]&&v.setRequestHeader("If-None-Match",n.etag[d])),(k.data&&k.hasContent&&k.contentType!==!1||b.contentType)&&v.setRequestHeader("Content-Type",k.contentType),v.setRequestHeader("Accept",k.dataTypes[0]&&k.accepts[k.dataTypes[0]]?k.accepts[k.dataTypes[0]]+("*"!==k.dataTypes[0]?", "+nb+"; q=0.01":""):k.accepts["*"]);for(j in k.headers)v.setRequestHeader(j,k.headers[j]);if(k.beforeSend&&(k.beforeSend.call(l,v,k)===!1||2===t))return v.abort();u="abort";for(j in{success:1,error:1,complete:1})v[j](k[j]);if(c=rb(mb,k,b,v)){v.readyState=1,i&&m.trigger("ajaxSend",[v,k]),k.async&&k.timeout>0&&(g=setTimeout(function(){v.abort("timeout")},k.timeout));try{t=1,c.send(r,x)}catch(w){if(!(2>t))throw w;x(-1,w)}}else x(-1,"No Transport");function x(a,b,f,h){var j,r,s,u,w,x=b;2!==t&&(t=2,g&&clearTimeout(g),c=void 0,e=h||"",v.readyState=a>0?4:0,j=a>=200&&300>a||304===a,f&&(u=tb(k,v,f)),u=ub(k,u,v,j),j?(k.ifModified&&(w=v.getResponseHeader("Last-Modified"),w&&(n.lastModified[d]=w),w=v.getResponseHeader("etag"),w&&(n.etag[d]=w)),204===a||"HEAD"===k.type?x="nocontent":304===a?x="notmodified":(x=u.state,r=u.data,s=u.error,j=!s)):(s=x,(a||!x)&&(x="error",0>a&&(a=0))),v.status=a,v.statusText=(b||x)+"",j?o.resolveWith(l,[r,x,v]):o.rejectWith(l,[v,x,s]),v.statusCode(q),q=void 0,i&&m.trigger(j?"ajaxSuccess":"ajaxError",[v,k,j?r:s]),p.fireWith(l,[v,x]),i&&(m.trigger("ajaxComplete",[v,k]),--n.active||n.event.trigger("ajaxStop")))}return v},getJSON:function(a,b,c){return n.get(a,b,c,"json")},getScript:function(a,b){return n.get(a,void 0,b,"script")}}),n.each(["get","post"],function(a,b){n[b]=function(a,c,d,e){return n.isFunction(c)&&(e=e||d,d=c,c=void 0),n.ajax({url:a,type:b,dataType:e,data:c,success:d})}}),n._evalUrl=function(a){return n.ajax({url:a,type:"GET",dataType:"script",async:!1,global:!1,"throws":!0})},n.fn.extend({wrapAll:function(a){var b;return n.isFunction(a)?this.each(function(b){n(this).wrapAll(a.call(this,b))}):(this[0]&&(b=n(a,this[0].ownerDocument).eq(0).clone(!0),this[0].parentNode&&b.insertBefore(this[0]),b.map(function(){var a=this;while(a.firstElementChild)a=a.firstElementChild;return a}).append(this)),this)},wrapInner:function(a){return this.each(n.isFunction(a)?function(b){n(this).wrapInner(a.call(this,b))}:function(){var b=n(this),c=b.contents();c.length?c.wrapAll(a):b.append(a)})},wrap:function(a){var b=n.isFunction(a);return this.each(function(c){n(this).wrapAll(b?a.call(this,c):a)})},unwrap:function(){return this.parent().each(function(){n.nodeName(this,"body")||n(this).replaceWith(this.childNodes)}).end()}}),n.expr.filters.hidden=function(a){return a.offsetWidth<=0&&a.offsetHeight<=0},n.expr.filters.visible=function(a){return!n.expr.filters.hidden(a)};var vb=/%20/g,wb=/\[\]$/,xb=/\r?\n/g,yb=/^(?:submit|button|image|reset|file)$/i,zb=/^(?:input|select|textarea|keygen)/i;function Ab(a,b,c,d){var e;if(n.isArray(b))n.each(b,function(b,e){c||wb.test(a)?d(a,e):Ab(a+"["+("object"==typeof e?b:"")+"]",e,c,d)});else if(c||"object"!==n.type(b))d(a,b);else for(e in b)Ab(a+"["+e+"]",b[e],c,d)}n.param=function(a,b){var c,d=[],e=function(a,b){b=n.isFunction(b)?b():null==b?"":b,d[d.length]=encodeURIComponent(a)+"="+encodeURIComponent(b)};if(void 0===b&&(b=n.ajaxSettings&&n.ajaxSettings.traditional),n.isArray(a)||a.jquery&&!n.isPlainObject(a))n.each(a,function(){e(this.name,this.value)});else for(c in a)Ab(c,a[c],b,e);return d.join("&").replace(vb,"+")},n.fn.extend({serialize:function(){return n.param(this.serializeArray())},serializeArray:function(){return this.map(function(){var a=n.prop(this,"elements");return a?n.makeArray(a):this}).filter(function(){var a=this.type;return this.name&&!n(this).is(":disabled")&&zb.test(this.nodeName)&&!yb.test(a)&&(this.checked||!T.test(a))}).map(function(a,b){var c=n(this).val();return null==c?null:n.isArray(c)?n.map(c,function(a){return{name:b.name,value:a.replace(xb,"\r\n")}}):{name:b.name,value:c.replace(xb,"\r\n")}}).get()}}),n.ajaxSettings.xhr=function(){try{return new XMLHttpRequest}catch(a){}};var Bb=0,Cb={},Db={0:200,1223:204},Eb=n.ajaxSettings.xhr();a.attachEvent&&a.attachEvent("onunload",function(){for(var a in Cb)Cb[a]()}),k.cors=!!Eb&&"withCredentials"in Eb,k.ajax=Eb=!!Eb,n.ajaxTransport(function(a){var b;return k.cors||Eb&&!a.crossDomain?{send:function(c,d){var e,f=a.xhr(),g=++Bb;if(f.open(a.type,a.url,a.async,a.username,a.password),a.xhrFields)for(e in a.xhrFields)f[e]=a.xhrFields[e];a.mimeType&&f.overrideMimeType&&f.overrideMimeType(a.mimeType),a.crossDomain||c["X-Requested-With"]||(c["X-Requested-With"]="XMLHttpRequest");for(e in c)f.setRequestHeader(e,c[e]);b=function(a){return function(){b&&(delete Cb[g],b=f.onload=f.onerror=null,"abort"===a?f.abort():"error"===a?d(f.status,f.statusText):d(Db[f.status]||f.status,f.statusText,"string"==typeof f.responseText?{text:f.responseText}:void 0,f.getAllResponseHeaders()))}},f.onload=b(),f.onerror=b("error"),b=Cb[g]=b("abort");try{f.send(a.hasContent&&a.data||null)}catch(h){if(b)throw h}},abort:function(){b&&b()}}:void 0}),n.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/(?:java|ecma)script/},converters:{"text script":function(a){return n.globalEval(a),a}}}),n.ajaxPrefilter("script",function(a){void 0===a.cache&&(a.cache=!1),a.crossDomain&&(a.type="GET")}),n.ajaxTransport("script",function(a){if(a.crossDomain){var b,c;return{send:function(d,e){b=n("<script>").prop({async:!0,charset:a.scriptCharset,src:a.url}).on("load error",c=function(a){b.remove(),c=null,a&&e("error"===a.type?404:200,a.type)}),l.head.appendChild(b[0])},abort:function(){c&&c()}}}});var Fb=[],Gb=/(=)\?(?=&|$)|\?\?/;n.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var a=Fb.pop()||n.expando+"_"+cb++;return this[a]=!0,a}}),n.ajaxPrefilter("json jsonp",function(b,c,d){var e,f,g,h=b.jsonp!==!1&&(Gb.test(b.url)?"url":"string"==typeof b.data&&!(b.contentType||"").indexOf("application/x-www-form-urlencoded")&&Gb.test(b.data)&&"data");return h||"jsonp"===b.dataTypes[0]?(e=b.jsonpCallback=n.isFunction(b.jsonpCallback)?b.jsonpCallback():b.jsonpCallback,h?b[h]=b[h].replace(Gb,"$1"+e):b.jsonp!==!1&&(b.url+=(db.test(b.url)?"&":"?")+b.jsonp+"="+e),b.converters["script json"]=function(){return g||n.error(e+" was not called"),g[0]},b.dataTypes[0]="json",f=a[e],a[e]=function(){g=arguments},d.always(function(){a[e]=f,b[e]&&(b.jsonpCallback=c.jsonpCallback,Fb.push(e)),g&&n.isFunction(f)&&f(g[0]),g=f=void 0}),"script"):void 0}),n.parseHTML=function(a,b,c){if(!a||"string"!=typeof a)return null;"boolean"==typeof b&&(c=b,b=!1),b=b||l;var d=v.exec(a),e=!c&&[];return d?[b.createElement(d[1])]:(d=n.buildFragment([a],b,e),e&&e.length&&n(e).remove(),n.merge([],d.childNodes))};var Hb=n.fn.load;n.fn.load=function(a,b,c){if("string"!=typeof a&&Hb)return Hb.apply(this,arguments);var d,e,f,g=this,h=a.indexOf(" ");return h>=0&&(d=n.trim(a.slice(h)),a=a.slice(0,h)),n.isFunction(b)?(c=b,b=void 0):b&&"object"==typeof b&&(e="POST"),g.length>0&&n.ajax({url:a,type:e,dataType:"html",data:b}).done(function(a){f=arguments,g.html(d?n("<div>").append(n.parseHTML(a)).find(d):a)}).complete(c&&function(a,b){g.each(c,f||[a.responseText,b,a])}),this},n.each(["ajaxStart","ajaxStop","ajaxComplete","ajaxError","ajaxSuccess","ajaxSend"],function(a,b){n.fn[b]=function(a){return this.on(b,a)}}),n.expr.filters.animated=function(a){return n.grep(n.timers,function(b){return a===b.elem}).length};var Ib=a.document.documentElement;function Jb(a){return n.isWindow(a)?a:9===a.nodeType&&a.defaultView}n.offset={setOffset:function(a,b,c){var d,e,f,g,h,i,j,k=n.css(a,"position"),l=n(a),m={};"static"===k&&(a.style.position="relative"),h=l.offset(),f=n.css(a,"top"),i=n.css(a,"left"),j=("absolute"===k||"fixed"===k)&&(f+i).indexOf("auto")>-1,j?(d=l.position(),g=d.top,e=d.left):(g=parseFloat(f)||0,e=parseFloat(i)||0),n.isFunction(b)&&(b=b.call(a,c,h)),null!=b.top&&(m.top=b.top-h.top+g),null!=b.left&&(m.left=b.left-h.left+e),"using"in b?b.using.call(a,m):l.css(m)}},n.fn.extend({offset:function(a){if(arguments.length)return void 0===a?this:this.each(function(b){n.offset.setOffset(this,a,b)});var b,c,d=this[0],e={top:0,left:0},f=d&&d.ownerDocument;if(f)return b=f.documentElement,n.contains(b,d)?(typeof d.getBoundingClientRect!==U&&(e=d.getBoundingClientRect()),c=Jb(f),{top:e.top+c.pageYOffset-b.clientTop,left:e.left+c.pageXOffset-b.clientLeft}):e},position:function(){if(this[0]){var a,b,c=this[0],d={top:0,left:0};return"fixed"===n.css(c,"position")?b=c.getBoundingClientRect():(a=this.offsetParent(),b=this.offset(),n.nodeName(a[0],"html")||(d=a.offset()),d.top+=n.css(a[0],"borderTopWidth",!0),d.left+=n.css(a[0],"borderLeftWidth",!0)),{top:b.top-d.top-n.css(c,"marginTop",!0),left:b.left-d.left-n.css(c,"marginLeft",!0)}}},offsetParent:function(){return this.map(function(){var a=this.offsetParent||Ib;while(a&&!n.nodeName(a,"html")&&"static"===n.css(a,"position"))a=a.offsetParent;return a||Ib})}}),n.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(b,c){var d="pageYOffset"===c;n.fn[b]=function(e){return J(this,function(b,e,f){var g=Jb(b);return void 0===f?g?g[c]:b[e]:void(g?g.scrollTo(d?a.pageXOffset:f,d?f:a.pageYOffset):b[e]=f)},b,e,arguments.length,null)}}),n.each(["top","left"],function(a,b){n.cssHooks[b]=ya(k.pixelPosition,function(a,c){return c?(c=xa(a,b),va.test(c)?n(a).position()[b]+"px":c):void 0})}),n.each({Height:"height",Width:"width"},function(a,b){n.each({padding:"inner"+a,content:b,"":"outer"+a},function(c,d){n.fn[d]=function(d,e){var f=arguments.length&&(c||"boolean"!=typeof d),g=c||(d===!0||e===!0?"margin":"border");return J(this,function(b,c,d){var e;return n.isWindow(b)?b.document.documentElement["client"+a]:9===b.nodeType?(e=b.documentElement,Math.max(b.body["scroll"+a],e["scroll"+a],b.body["offset"+a],e["offset"+a],e["client"+a])):void 0===d?n.css(b,c,g):n.style(b,c,d,g)},b,f?d:void 0,f,null)}})}),n.fn.size=function(){return this.length},n.fn.andSelf=n.fn.addBack,"function"==typeof define&&define.amd&&define("jquery",[],function(){return n});var Kb=a.jQuery,Lb=a.$;return n.noConflict=function(b){return a.$===n&&(a.$=Lb),b&&a.jQuery===n&&(a.jQuery=Kb),n},typeof b===U&&(a.jQuery=a.$=n),n});
//# sourceMappingURL=jquery.min.map
;
/*!
 * Mobiscroll v2.17.1
 * http://mobiscroll.com
 *
 * Copyright 2010-2015, Acid Media
 * Licensed under the MIT license.
 *
 */
(function ($, undefined) {

    function testProps(props) {
        var i;
        for (i in props) {
            if (mod[props[i]] !== undefined) {
                return true;
            }
        }
        return false;
    }

    function testPrefix() {
        var prefixes = ['Webkit', 'Moz', 'O', 'ms'],
            p;

        for (p in prefixes) {
            if (testProps([prefixes[p] + 'Transform'])) {
                return '-' + prefixes[p].toLowerCase() + '-';
            }
        }
        return '';
    }

    function init(that, options, args) {
        var ret = that;

        // Init
        if (typeof options === 'object') {
            return that.each(function () {
                if (instances[this.id]) {
                    instances[this.id].destroy();
                }
                new $.mobiscroll.classes[options.component || 'Scroller'](this, options);
            });
        }

        // Method call
        if (typeof options === 'string') {
            that.each(function () {
                var r,
                    inst = instances[this.id];

                if (inst && inst[options]) {
                    r = inst[options].apply(this, Array.prototype.slice.call(args, 1));
                    if (r !== undefined) {
                        ret = r;
                        return false;
                    }
                }
            });
        }

        return ret;
    }

    var ms,
        id = +new Date(),
        instances = {},
        extend = $.extend,
        mod = document.createElement('modernizr').style,
        has3d = testProps(['perspectiveProperty', 'WebkitPerspective', 'MozPerspective', 'OPerspective', 'msPerspective']),
        hasFlex = testProps(['flex', 'msFlex', 'WebkitBoxDirection']),
        prefix = testPrefix(),
        pr = prefix.replace(/^\-/, '').replace(/\-$/, '').replace('moz', 'Moz');

    $.fn.mobiscroll = function (method) {
        extend(this, $.mobiscroll.components);
        return init(this, method, arguments);
    };

    ms = $.mobiscroll = $.mobiscroll || {
        version: '2.17.1',
        util: {
            prefix: prefix,
            jsPrefix: pr,
            has3d: has3d,
            hasFlex: hasFlex,
            isOldAndroid: /android [1-3]/i.test(navigator.userAgent),
            preventClick: function () {
                // Prevent ghost click
                ms.tapped++;
                setTimeout(function () {
                    ms.tapped--;
                }, 500);
            },
            testTouch: function (e, elm) {
                if (e.type == 'touchstart') {
                    $(elm).attr('data-touch', '1');
                } else if ($(elm).attr('data-touch')) {
                    $(elm).removeAttr('data-touch');
                    return false;
                }
                return true;
            },
            objectToArray: function (obj) {
                var arr = [],
                    i;

                for (i in obj) {
                    arr.push(obj[i]);
                }

                return arr;
            },
            arrayToObject: function (arr) {
                var obj = {},
                    i;

                if (arr) {
                    for (i = 0; i < arr.length; i++) {
                        obj[arr[i]] = arr[i];
                    }
                }

                return obj;
            },
            isNumeric: function (a) {
                return a - parseFloat(a) >= 0;
            },
            isString: function (s) {
                return typeof s === 'string';
            },
            getCoord: function (e, c, page) {
                var ev = e.originalEvent || e,
                    prop = (page ? 'page' : 'client') + c;
                return ev.changedTouches ? ev.changedTouches[0][prop] : e[prop];
            },
            getPosition: function (t, vertical) {
                var style = window.getComputedStyle ? getComputedStyle(t[0]) : t[0].style,
                    matrix,
                    px;

                if (has3d) {
                    $.each(['t', 'webkitT', 'MozT', 'OT', 'msT'], function (i, v) {
                        if (style[v + 'ransform'] !== undefined) {
                            matrix = style[v + 'ransform'];
                            return false;
                        }
                    });
                    matrix = matrix.split(')')[0].split(', ');
                    px = vertical ? (matrix[13] || matrix[5]) : (matrix[12] || matrix[4]);
                } else {
                    px = vertical ? style.top.replace('px', '') : style.left.replace('px', '');
                }

                return px;
            },
            addIcon: function ($control, ic) {
                var icons = {},
                    $parent = $control.parent(),
                    errorMsg = $parent.find('.mbsc-err-msg'),
                    align = $control.attr('data-icon-align') || 'left',
                    icon = $control.attr('data-icon');

                // Wrap input
                $('<span class="mbsc-input-wrap"></span>').insertAfter($control).append($control);

                if (errorMsg) {
                    $parent.find('.mbsc-input-wrap').append(errorMsg);
                }

                if (icon) {
                    if (icon.indexOf('{') !== -1) {
                        icons = JSON.parse(icon);
                    } else {
                        icons[align] = icon;
                    }

                    extend(icons, ic);

                    $parent
                        .addClass((icons.right ? 'mbsc-ic-right ' : '') + (icons.left ? ' mbsc-ic-left' : ''))
                        .find('.mbsc-input-wrap')
                        .append(icons.left ? '<span class="mbsc-input-ic mbsc-left-ic mbsc-ic mbsc-ic-' + icons.left + '"></span>' : '')
                        .append(icons.right ? '<span class="mbsc-input-ic mbsc-right-ic mbsc-ic mbsc-ic-' + icons.right + '"></span>' : '');
                }
            },
            constrain: function (val, min, max) {
                return Math.max(min, Math.min(val, max));
            },
            vibrate: function (time) {
                if ('vibrate' in navigator) {
                    navigator.vibrate(time || 50);
                }
            }
        },
        tapped: 0,
        autoTheme: 'mobiscroll',
        presets: {
            scroller: {},
            numpad: {},
            listview: {},
            menustrip: {}
        },
        themes: {
            form: {},
            frame: {},
            listview: {},
            menustrip: {},
            progress: {}
        },
        i18n: {},
        instances: instances,
        classes: {},
        components: {},
        defaults: {
            context: 'body',
            mousewheel: true,
            vibrate: true
        },
        setDefaults: function (o) {
            extend(this.defaults, o);
        },
        presetShort: function (name, c, p) {
            this.components[name] = function (s) {
                return init(this, extend(s, {
                    component: c,
                    preset: p === false ? undefined : name
                }), arguments);
            };
        }
    };

    $.mobiscroll.classes.Base = function (el, settings) {

        var lang,
            preset,
            s,
            theme,
            themeName,
            defaults,
            ms = $.mobiscroll,
            util = ms.util,
            getCoord = util.getCoord,
            that = this;

        that.settings = {};

        that._presetLoad = function () {};

        that._init = function (ss) {
            s = that.settings;

            // Update original user settings
            extend(settings, ss);

            // Load user defaults
            if (that._hasDef) {
                defaults = ms.defaults;
            }

            // Create settings object
            extend(s, that._defaults, defaults, settings);

            // Get theme defaults
            if (that._hasTheme) {

                themeName = s.theme;

                if (themeName == 'auto' || !themeName) {
                    themeName = ms.autoTheme;
                }

                if (themeName == 'default') {
                    themeName = 'mobiscroll';
                }

                settings.theme = themeName;

                theme = ms.themes[that._class] ? ms.themes[that._class][themeName] : {};
            }

            // Get language defaults
            if (that._hasLang) {
                lang = ms.i18n[s.lang];
            }

            if (that._hasTheme) {
                that.trigger('onThemeLoad', [lang, settings]);
            }

            // Update settings object
            extend(s, theme, lang, defaults, settings);

            // Load preset settings
            if (that._hasPreset) {

                that._presetLoad(s);

                preset = ms.presets[that._class][s.preset];

                if (preset) {
                    preset = preset.call(el, that);
                    extend(s, preset, settings);
                }
            }
        };

        that._destroy = function () {
            that.trigger('onDestroy', []);

            // Delete scroller instance
            delete instances[el.id];

            that = null;
        };

        /**
         * Attach tap event to the given element.
         */
        that.tap = function (el, handler, prevent) {
            var startX,
                startY,
                target,
                moved;

            function onStart(ev) {
                if (!target) {
                    // Can't always call preventDefault here, it kills page scroll
                    if (prevent) {
                        ev.preventDefault();
                    }
                    target = this;
                    startX = getCoord(ev, 'X');
                    startY = getCoord(ev, 'Y');
                    moved = false;

                    if (ev.type == 'pointerdown') {
                        $(document)
                            .on('pointermove', onMove)
                            .on('pointerup', onEnd);
                    }
                }
            }

            function onMove(ev) {
                // If movement is more than 20px, don't fire the click event handler
                if (target && !moved && Math.abs(getCoord(ev, 'X') - startX) > 9 || Math.abs(getCoord(ev, 'Y') - startY) > 9) {
                    moved = true;
                }
            }

            function onEnd(ev) {
                if (target) {
                    if (!moved) {
                        ev.preventDefault();
                        handler.call(target, ev, that);
                    }

                    if (ev.type == 'pointerup') {
                        $(document)
                            .off('pointermove', onMove)
                            .off('pointerup', onEnd);
                    }

                    target = false;

                    util.preventClick();
                }
            }

            function onCancel() {
                target = false;
            }

            if (s.tap) {
                el
                    .on('touchstart.dw pointerdown.dw', onStart)
                    .on('touchcancel.dw pointercancel.dw', onCancel)
                    .on('touchmove.dw', onMove)
                    .on('touchend.dw', onEnd);
            }

            el.on('click.dw', function (ev) {
                ev.preventDefault();
                // If handler was not called on touchend, call it on click;
                handler.call(this, ev, that);
            });
        };

        /**
         * Triggers an event
         */
        that.trigger = function (name, args) {
            var ret;
            args.push(that);
            $.each([defaults, theme, preset, settings], function (i, v) {
                if (v && v[name]) { // Call preset event
                    ret = v[name].apply(el, args);
                }
            });
            return ret;
        };

        /**
         * Sets one ore more options.
         */
        that.option = function (opt, value) {
            var obj = {};
            if (typeof opt === 'object') {
                obj = opt;
            } else {
                obj[opt] = value;
            }
            that.init(obj);
        };

        /**
         * Returns the mobiscroll instance.
         */
        that.getInst = function () {
            return that;
        };

        settings = settings || {};

        $(el).addClass('mbsc-comp');

        // Autogenerate id
        if (!el.id) {
            el.id = 'mobiscroll' + (++id);
        }

        // Save instance
        instances[el.id] = that;
    };

    // Prevent standard behaviour on body click
    function preventClick(ev) {
        // Textarea needs the mousedown event
        if (ms.tapped && !ev.tap && !(ev.target.nodeName == 'TEXTAREA' && ev.type == 'mousedown')) {
            ev.stopPropagation();
            ev.preventDefault();
            return false;
        }
    }

    if (document.addEventListener) {
        $.each(['mouseover', 'mousedown', 'mouseup', 'click'], function (i, ev) {
            document.addEventListener(ev, preventClick, true);
        });
    }

})(jQuery);

;
(function ($, window, document, undefined) {
    var $activeElm,
        preventShow,
        ms = $.mobiscroll,
        util = ms.util,
        pr = util.jsPrefix,
        has3d = util.has3d,
        constrain = util.constrain,
        isString = util.isString,
        isOldAndroid = util.isOldAndroid,
        isIOS8 = /(iphone|ipod|ipad).* os 8_/i.test(navigator.userAgent),
        animEnd = 'webkitAnimationEnd animationend',
        empty = function () {},
        prevdef = function (ev) {
            ev.preventDefault();
        };

    ms.classes.Frame = function (el, settings, inherit) {
        var $ariaDiv,
            $ctx,
            $header,
            $markup,
            $overlay,
            $persp,
            $popup,
            $wnd,
            $wrapper,
            buttons,
            btn,
            doAnim,
            event,
            hasButtons,
            isModal,
            modalWidth,
            modalHeight,
            posEvents,
            preventPos,
            s,
            scrollLock,
            setReadOnly,
            wndWidth,
            wndHeight,

            that = this,
            $elm = $(el),
            elmList = [],
            posDebounce = {};

        function onBtnStart(ev) {
            // Can't call preventDefault here, it kills page scroll
            if (btn) {
                btn.removeClass('dwb-a');
            }

            btn = $(this);

            // Active button
            if (!btn.hasClass('dwb-d') && !btn.hasClass('dwb-nhl')) {
                btn.addClass('dwb-a');
            }

            if (ev.type === 'mousedown') {
                $(document).on('mouseup', onBtnEnd);
            } else if (ev.type === 'pointerdown') {
                $(document).on('pointerup', onBtnEnd);
            }
        }

        function onBtnEnd(ev) {
            if (btn) {
                btn.removeClass('dwb-a');
                btn = null;
            }

            if (ev.type === 'mouseup') {
                $(document).off('mouseup', onBtnEnd);
            } else if (ev.type === 'pointerup') {
                $(document).off('pointerup', onBtnEnd);
            }
        }

        function onWndKeyDown(ev) {
            if (ev.keyCode == 13) {
                that.select();
            } else if (ev.keyCode == 27) {
                that.cancel();
            }
        }

        function onShow(prevFocus) {
            if (!prevFocus) {
                $popup.focus();
            }
            that.ariaMessage(s.ariaMessage);
        }

        function onHide(prevAnim) {
            var activeEl,
                value,
                type,
                focus = s.focusOnClose;

            that._markupRemove();

            $markup.remove();

            if ($activeElm && !prevAnim) {
                setTimeout(function () {
                    if (focus === undefined || focus === true) {
                        preventShow = true;
                        activeEl = $activeElm[0];
                        type = activeEl.type;
                        value = activeEl.value;
                        try {
                            activeEl.type = 'button';
                        } catch (ex) {}
                        $activeElm.focus();
                        activeEl.type = type;
                        activeEl.value = value;
                    } else if (focus) {
                        $(focus).focus();
                    }
                }, 200);
            }

            that._isVisible = false;

            event('onHide', []);
        }

        function onPosition(ev) {
            clearTimeout(posDebounce[ev.type]);
            posDebounce[ev.type] = setTimeout(function () {
                var isScroll = ev.type == 'scroll';
                if (isScroll && !scrollLock) {
                    return;
                }
                that.position(!isScroll);
            }, 200);
        }

        function onFocus(ev) {
            if (ev.target.nodeType && !$popup[0].contains(ev.target)) {
                $popup.focus();
            }
        }

        function onBlur() {
            $(this).off('blur', onBlur);
            setTimeout(function () {
                that.position();
            }, 100);
        }

        function show(beforeShow, $elm) {
            if (beforeShow) {
                beforeShow();
            }

            // Hide virtual keyboard
            if ($(document.activeElement).is('input,textarea')) {
                $(document.activeElement).blur();
            }

            if (that.show() !== false) {
                $activeElm = $elm;

                setTimeout(function () {
                    preventShow = false;
                }, 300); // With jQuery < 1.9 focus is fired twice in IE
            }
        }

        function set() {
            that._fillValue();
            event('onSelect', [that._value]);
        }

        function cancel() {
            event('onCancel', [that._value]);
        }

        function clear() {
            that.setVal(null, true);
        }

        // Call the parent constructor
        ms.classes.Base.call(this, el, settings, true);

        /**
         * Positions the scroller on the screen.
         */
        that.position = function (check) {
            var w,
                l,
                t,
                anchor,
                aw, // anchor width
                ah, // anchor height
                ap, // anchor position
                at, // anchor top
                al, // anchor left
                arr, // arrow
                arrw, // arrow width
                arrl, // arrow left
                dh,
                scroll,
                sl, // scroll left
                st, // scroll top
                totalw = 0,
                minw = 0,
                css = {},
                nw = Math.min($wnd[0].innerWidth || $wnd.innerWidth(), $persp.width()), //$persp.width(), // To get the width without scrollbar
                nh = $wnd[0].innerHeight || $wnd.innerHeight(),
                $focused = $(document.activeElement);

            if ($focused.is('input,textarea') && !/(button|submit|checkbox|radio)/.test($focused.attr('type'))) {
                $focused.on('blur', onBlur);
                return;
            }

            if ((wndWidth === nw && wndHeight === nh && check) || preventPos) {
                return;
            }

            if (that._isFullScreen || /top|bottom/.test(s.display)) {
                // Set width, if document is larger than viewport, needs to be set before onPosition (for calendar)
                $popup.width(nw);
            }

            if (event('onPosition', [$markup, nw, nh]) === false || !isModal) {
                return;
            }

            sl = $wnd.scrollLeft();
            st = $wnd.scrollTop();
            anchor = s.anchor === undefined ? $elm : $(s.anchor);

            // Set / unset liquid layout based on screen width, but only if not set explicitly by the user
            if (that._isLiquid && s.layout !== 'liquid') {
                if (nw < 400) {
                    $markup.addClass('dw-liq');
                } else {
                    $markup.removeClass('dw-liq');
                }
            }

            if (!that._isFullScreen && /modal|bubble/.test(s.display)) {
                $wrapper.width('');
                $('.mbsc-w-p', $markup).each(function () {
                    w = $(this).outerWidth(true);
                    totalw += w;
                    minw = (w > minw) ? w : minw;
                });
                w = totalw > nw ? minw : totalw;
                $wrapper.width(w + 1).css('white-space', totalw > nw ? '' : 'nowrap');
            }

            modalWidth = $popup.outerWidth();
            modalHeight = $popup.outerHeight(true);
            scrollLock = modalHeight <= nh && modalWidth <= nw;

            that.scrollLock = scrollLock;

            if (scrollLock) {
                $ctx.addClass('mbsc-fr-lock');
            } else {
                $ctx.removeClass('mbsc-fr-lock');
            }

            if (s.display == 'modal') {
                l = Math.max(0, sl + (nw - modalWidth) / 2);
                t = st + (nh - modalHeight) / 2;
            } else if (s.display == 'bubble') {
                // Scroll only if width also changed
                // to prevent scroll when address bar appears / hides
                scroll = wndWidth !== nw;
                arr = $('.dw-arrw-i', $markup);
                ap = anchor.offset();
                at = Math.abs($ctx.offset().top - ap.top);
                al = Math.abs($ctx.offset().left - ap.left);

                // horizontal positioning
                aw = anchor.outerWidth();
                ah = anchor.outerHeight();
                l = constrain(al - ($popup.outerWidth(true) - aw) / 2, sl + 3, sl + nw - modalWidth - 3);

                // vertical positioning
                t = at - modalHeight; // above the input
                if ((t < st) || (at > st + nh)) { // if doesn't fit above or the input is out of the screen
                    $popup.removeClass('dw-bubble-top').addClass('dw-bubble-bottom');
                    t = at + ah; // below the input
                } else {
                    $popup.removeClass('dw-bubble-bottom').addClass('dw-bubble-top');
                }

                // Calculate Arrow position
                arrw = arr.outerWidth();
                arrl = constrain(al + aw / 2 - (l + (modalWidth - arrw) / 2), 0, arrw);

                // Limit Arrow position
                $('.dw-arr', $markup).css({
                    left: arrl
                });
            } else {
                l = sl;
                if (s.display == 'top') {
                    t = st;
                } else if (s.display == 'bottom') {
                    t = st + nh - modalHeight;
                }
            }

            t = t < 0 ? 0 : t;

            css.top = t;
            css.left = l;
            $popup.css(css);

            // If top + modal height > doc height, increase doc height
            $persp.height(0);
            dh = Math.max(t + modalHeight, s.context == 'body' ? $(document).height() : $ctx[0].scrollHeight);
            $persp.css({
                height: dh
            });

            // Scroll needed
            if (scroll && ((t + modalHeight > st + nh) || (at > st + nh))) {
                preventPos = true;
                setTimeout(function () {
                    preventPos = false;
                }, 300);
                $wnd.scrollTop(Math.min(at, t + modalHeight - nh, dh - nh));
            }

            wndWidth = nw;
            wndHeight = nh;

            // Call position for nested mobiscroll components
            $('.mbsc-comp', $markup).each(function () {
                var inst = $(this).mobiscroll('getInst');
                if (inst !== that && inst.position) {
                    inst.position();
                }
            });
        };

        /**
         * Show mobiscroll on focus and click event of the parameter.
         * @param {jQuery} $elm - Events will be attached to this element.
         * @param {Function} [beforeShow=undefined] - Optional function to execute before showing mobiscroll.
         */
        that.attachShow = function ($elm, beforeShow) {
            elmList.push({
                readOnly: $elm.prop('readonly'),
                el: $elm
            });
            if (s.display !== 'inline') {
                if (setReadOnly && $elm.is('input')) {
                    $elm.prop('readonly', true).on('mousedown.dw', function (ev) {
                        // Prevent input to get focus on tap (virtual keyboard pops up on some devices)
                        ev.preventDefault();
                    });
                }

                if (s.showOnFocus) {
                    $elm.on('focus.dw', function () {
                        if (!preventShow) {
                            show(beforeShow, $elm);
                        }
                    });
                }

                if (s.showOnTap) {
                    $elm.on('keydown.dw', function (ev) {
                        if (ev.keyCode == 32 || ev.keyCode == 13) { // Space or Enter
                            ev.preventDefault();
                            ev.stopPropagation();
                            show(beforeShow, $elm);
                        }
                    });

                    that.tap($elm, function () {
                        show(beforeShow, $elm);
                    });
                }
            }
        };

        /**
         * Set button handler.
         */
        that.select = function () {
            if (isModal) {
                that.hide(false, 'set', false, set);
            } else {
                set();
            }
        };

        /**
         * Cancel and hide the scroller instance.
         */
        that.cancel = function () {
            if (isModal) {
                that.hide(false, 'cancel', false, cancel);
            } else {
                set();
            }
        };

        /**
         * Clear button handler.
         */
        that.clear = function () {
            event('onClear', [$markup]);
            if (isModal && that._isVisible && !that.live) {
                that.hide(false, 'clear', false, clear);
            } else {
                clear();
            }
        };

        /**
         * Enables the scroller and the associated input.
         */
        that.enable = function () {
            s.disabled = false;
            if (that._isInput) {
                $elm.prop('disabled', false);
            }
        };

        /**
         * Disables the scroller and the associated input.
         */
        that.disable = function () {
            s.disabled = true;
            if (that._isInput) {
                $elm.prop('disabled', true);
            }
        };

        /**
         * Shows the scroller instance.
         * @param {Boolean} prevAnim - Prevent animation if true
         * @param {Boolean} prevFocus - Prevent focusing if true
         */
        that.show = function (prevAnim, prevFocus) {
            // Create wheels
            var html;

            if (s.disabled || that._isVisible) {
                return;
            }

            // Parse value from input
            that._readValue();

            if (event('onBeforeShow', []) === false) {
                return false;
            }

            doAnim = isOldAndroid ? false : s.animate;

            if (doAnim !== false) {
                if (s.display == 'top') {
                    doAnim = 'slidedown';
                }
                if (s.display == 'bottom') {
                    doAnim = 'slideup';
                }
            }

            // Create wheels containers
            html = '<div lang="' + s.lang + '" class="mbsc-' + s.theme + (s.baseTheme ? ' mbsc-' + s.baseTheme : '') + ' dw-' + s.display + ' ' +
                (s.cssClass || '') +
                (that._isLiquid ? ' dw-liq' : '') +
                (isOldAndroid ? ' mbsc-old' : '') +
                (hasButtons ? '' : ' dw-nobtn') + '">' +
                '<div class="dw-persp">' +
                (isModal ? '<div class="dwo"></div>' : '') + // Overlay
                '<div' + (isModal ? ' role="dialog" tabindex="-1"' : '') + ' class="dw' + (s.rtl ? ' dw-rtl' : ' dw-ltr') + '">' + // Popup
                (s.display === 'bubble' ? '<div class="dw-arrw"><div class="dw-arrw-i"><div class="dw-arr"></div></div></div>' : '') + // Bubble arrow
                '<div class="dwwr">' + // Popup content
                '<div aria-live="assertive" class="dw-aria dw-hidden"></div>' +
                (s.headerText ? '<div class="dwv">' + (isString(s.headerText) ? s.headerText : '') + '</div>' : '') + // Header
                '<div class="dwcc">'; // Wheel group container

            html += that._generateContent();

            html += '</div>';

            if (hasButtons) {
                html += '<div class="dwbc">';
                $.each(buttons, function (i, b) {
                    b = isString(b) ? that.buttons[b] : b;

                    if (b.handler === 'set') {
                        b.parentClass = 'dwb-s';
                    }

                    if (b.handler === 'cancel') {
                        b.parentClass = 'dwb-c';
                    }

                    html += '<div' + (s.btnWidth ? ' style="width:' + (100 / buttons.length) + '%"' : '') + ' class="dwbw ' + (b.parentClass || '') + '"><div tabindex="0" role="button" class="dwb' + i + ' dwb-e ' + (b.cssClass === undefined ? s.btnClass : b.cssClass) + (b.icon ? ' mbsc-ic mbsc-ic-' + b.icon : '') + '">' + (b.text || '') + '</div></div>';
                });
                html += '</div>';
            }
            html += '</div></div></div></div>';

            $markup = $(html);
            $persp = $('.dw-persp', $markup);
            $overlay = $('.dwo', $markup);
            $wrapper = $('.dwwr', $markup);
            $header = $('.dwv', $markup);
            $popup = $('.dw', $markup);
            $ariaDiv = $('.dw-aria', $markup);

            that._markup = $markup;
            that._header = $header;
            that._isVisible = true;

            posEvents = 'orientationchange resize';

            that._markupReady($markup);

            event('onMarkupReady', [$markup]);

            // Show
            if (isModal) {
                // Enter / ESC
                $(window).on('keydown', onWndKeyDown);

                // Prevent scroll if not specified otherwise
                if (s.scrollLock) {
                    $markup.on('touchmove mousewheel wheel', function (ev) {
                        if (scrollLock) {
                            ev.preventDefault();
                        }
                    });
                }

                // Disable inputs to prevent bleed through (Android bug)
                if (pr !== 'Moz') {
                    $('input,select,button', $ctx).each(function () {
                        if (!this.disabled) {
                            $(this).addClass('dwtd').prop('disabled', true);
                        }
                    });
                }

                if (ms.activeInstance) {
                    ms.activeInstance.hide();
                }

                posEvents += ' scroll';

                ms.activeInstance = that;

                $markup.appendTo($ctx);

                if (s.focusTrap) {
                    $wnd.on('focusin', onFocus);
                }

                if (has3d && doAnim && !prevAnim) {
                    $markup.addClass('dw-in dw-trans').on(animEnd, function () {
                        $markup.off(animEnd).removeClass('dw-in dw-trans').find('.dw').removeClass('dw-' + doAnim);
                        onShow(prevFocus);
                    }).find('.dw').addClass('dw-' + doAnim);
                }
            } else if ($elm.is('div') && !that._hasContent) {
                $elm.html($markup);
            } else {
                $markup.insertAfter($elm);
            }

            that._markupInserted($markup);

            event('onMarkupInserted', [$markup]);

            // Set position
            that.position();

            $wnd.on(posEvents, onPosition);

            // Events
            $markup
                .on('selectstart mousedown', prevdef) // Prevents blue highlight on Android and text selection in IE
                .on('click', '.dwb-e', prevdef)
                .on('keydown', '.dwb-e', function (ev) {
                    if (ev.keyCode == 32) { // Space
                        ev.preventDefault();
                        ev.stopPropagation();
                        $(this).click();
                    }
                })
                .on('keydown', function (ev) { // Trap focus inside modal
                    if (ev.keyCode == 32) { // Space
                        ev.preventDefault();
                    } else if (ev.keyCode == 9 && isModal && s.focusTrap) { // Tab
                        var $focusable = $markup.find('[tabindex="0"]').filter(function () {
                                return this.offsetWidth > 0 || this.offsetHeight > 0;
                            }),
                            index = $focusable.index($(':focus', $markup)),
                            i = $focusable.length - 1,
                            target = 0;

                        if (ev.shiftKey) {
                            i = 0;
                            target = -1;
                        }

                        if (index === i) {
                            $focusable.eq(target).focus();
                            ev.preventDefault();
                        }
                    }
                });

            $('input,select,textarea', $markup).on('selectstart mousedown', function (ev) {
                ev.stopPropagation();
            }).on('keydown', function (ev) {
                if (ev.keyCode == 32) { // Space
                    ev.stopPropagation();
                }
            });

            //setTimeout(function () {
            // Init buttons
            $.each(buttons, function (i, b) {
                that.tap($('.dwb' + i, $markup), function (ev) {
                    b = isString(b) ? that.buttons[b] : b;
                    (isString(b.handler) ? that.handlers[b.handler] : b.handler).call(this, ev, that);
                }, true);
            });

            if (s.closeOnOverlay) {
                that.tap($overlay, function () {
                    that.cancel();
                });
            }

            if (isModal && !doAnim) {
                onShow(prevFocus);
            }

            $markup
                .on('touchstart mousedown pointerdown', '.dwb-e', onBtnStart)
                .on('touchend', '.dwb-e', onBtnEnd);

            that._attachEvents($markup);
            //}, 300);

            event('onShow', [$markup, that._tempValue]);
        };

        /**
         * Hides the scroller instance.
         */
        that.hide = function (prevAnim, btn, force, callback) {
            // If onClose handler returns false, prevent hide
            if (!that._isVisible || (!force && !that._isValid && btn == 'set') || (!force && event('onBeforeClose', [that._tempValue, btn]) === false)) {
                return false;
            }

            // Hide wheels and overlay
            if ($markup) {
                // Re-enable temporary disabled fields
                if (pr !== 'Moz') {
                    $('.dwtd', $ctx).each(function () {
                        $(this).prop('disabled', false).removeClass('dwtd');
                    });
                }

                if (has3d && isModal && doAnim && !prevAnim && !$markup.hasClass('dw-trans')) { // If dw-trans class was not removed, means that there was no animation
                    $markup.addClass('dw-out dw-trans').on(animEnd, function () {
                        onHide(prevAnim);
                    }).find('.dw').addClass('dw-' + doAnim);
                } else {
                    onHide(prevAnim);
                }

                // Stop positioning on window resize
                $wnd
                    .off(posEvents, onPosition)
                    .off('focusin', onFocus);
            }

            if (isModal) {
                $ctx.removeClass('mbsc-fr-lock');
                $(window).off('keydown', onWndKeyDown);
                delete ms.activeInstance;
            }

            if (callback) {
                callback();
            }

            event('onClosed', [that._value]);

        };

        that.ariaMessage = function (txt) {
            $ariaDiv.html('');
            setTimeout(function () {
                $ariaDiv.html(txt);
            }, 100);
        };

        /**
         * Return true if the scroller is currently visible.
         */
        that.isVisible = function () {
            return that._isVisible;
        };

        // Protected functions to override

        that.setVal = empty;

        that.getVal = empty;

        that._generateContent = empty;

        that._attachEvents = empty;

        that._readValue = empty;

        that._fillValue = empty;

        that._markupReady = empty;

        that._markupInserted = empty;

        that._markupRemove = empty;

        that._processSettings = empty;

        that._presetLoad = function (s) {
            // Add default buttons
            s.buttons = s.buttons || (s.display !== 'inline' ? ['set', 'cancel'] : []);

            // Hide header text in inline mode by default
            s.headerText = s.headerText === undefined ? (s.display !== 'inline' ? '{value}' : false) : s.headerText;
        };

        // Generic frame functions

        /**
         * Destroys the mobiscroll instance.
         */
        that.destroy = function () {
            // Force hide without animation
            that.hide(true, false, true);

            // Remove all events from elements
            $.each(elmList, function (i, v) {
                v.el.off('.dw').prop('readonly', v.readOnly);
            });

            that._destroy();
        };

        /**
         * Scroller initialization.
         */
        that.init = function (ss) {
            // @deprecated since 2.17.0, backward compatibility code
            // ---
            if (ss.onClose) {
                ss.onBeforeClose = ss.onClose;
            }
            // ---

            that._init(ss);

            that._isLiquid = (s.layout || (/top|bottom/.test(s.display) ? 'liquid' : '')) === 'liquid';

            that._processSettings();

            // Unbind all events (if re-init)
            $elm.off('.dw');

            buttons = s.buttons || [];
            isModal = s.display !== 'inline';
            setReadOnly = s.showOnFocus || s.showOnTap;

            that._window = $wnd = $(s.context == 'body' ? window : s.context);
            that._context = $ctx = $(s.context);

            that.live = true;

            // If no set button is found, live mode is activated
            $.each(buttons, function (i, b) {
                if (b == 'ok' || b == 'set' || b.handler == 'set') {
                    that.live = false;
                    return false;
                }
            });

            that.buttons.set = {
                text: s.setText,
                handler: 'set'
            };

            that.buttons.cancel = {
                text: (that.live) ? s.closeText : s.cancelText,
                handler: 'cancel'
            };

            that.buttons.clear = {
                text: s.clearText,
                handler: 'clear'
            };

            that._isInput = $elm.is('input');

            hasButtons = buttons.length > 0;

            if (that._isVisible) {
                that.hide(true, false, true);
            }

            event('onInit', []);

            if (isModal) {
                that._readValue();
                if (!that._hasContent) {
                    that.attachShow($elm);
                }
            } else {
                that.show();
            }

            $elm.on('change.dw', function () {
                if (!that._preventChange) {
                    that.setVal($elm.val(), true, false);
                }
                that._preventChange = false;
            });
        };

        that.buttons = {};
        that.handlers = {
            set: that.select,
            cancel: that.cancel,
            clear: that.clear
        };

        that._value = null;

        that._isValid = true;
        that._isVisible = false;

        // Constructor

        s = that.settings;
        event = that.trigger;

        if (!inherit) {
            that.init(settings);
        }
    };

    ms.classes.Frame.prototype._defaults = {
        // Localization
        lang: 'en',
        setText: 'Set',
        selectedText: '{count} selected',
        closeText: 'Close',
        cancelText: 'Cancel',
        clearText: 'Clear',
        // Options
        disabled: false,
        closeOnOverlay: true,
        showOnFocus: false,
        showOnTap: true,
        display: 'modal',
        scrollLock: true,
        tap: true,
        btnClass: 'dwb',
        btnWidth: true,
        focusTrap: true,
        focusOnClose: !isIOS8 // Temporary for iOS8
    };

    ms.themes.frame.mobiscroll = {
        rows: 5,
        showLabel: false,
        headerText: false,
        btnWidth: false,
        selectedLineHeight: true,
        selectedLineBorder: 1,
        dateOrder: 'MMddyy',
        weekDays: 'min',
        checkIcon: 'ion-ios7-checkmark-empty',
        btnPlusClass: 'mbsc-ic mbsc-ic-arrow-down5',
        btnMinusClass: 'mbsc-ic mbsc-ic-arrow-up5',
        btnCalPrevClass: 'mbsc-ic mbsc-ic-arrow-left5',
        btnCalNextClass: 'mbsc-ic mbsc-ic-arrow-right5'
    };

    // Prevent re-show on window focus
    $(window).on('focus', function () {
        if ($activeElm) {
            preventShow = true;
        }
    });

})(jQuery, window, document);

;
(function ($, window, document, undefined) {

    var ms = $.mobiscroll,
        classes = ms.classes,
        util = ms.util,
        pr = util.jsPrefix,
        has3d = util.has3d,
        hasFlex = util.hasFlex,
        getCoord = util.getCoord,
        constrain = util.constrain,
        testTouch = util.testTouch;

    ms.presetShort('scroller', 'Scroller', false);

    classes.Scroller = function (el, settings, inherit) {
        var $markup,
            btn,
            isScrollable,
            itemHeight,
            multiple,
            pixels,
            s,
            scrollDebounce,
            trigger,

            click,
            moved,
            start,
            startTime,
            stop,
            p,
            min,
            max,
            target,
            index,
            lines,
            timer,
            that = this,
            $elm = $(el),
            iv = {},
            pos = {},
            wheels = [];

        // Event handlers

        function onStart(ev) {
            // Scroll start
            if (testTouch(ev, this) && !target && !click && !btn && !isReadOnly(this)) {
                // Prevent touch highlight
                ev.preventDefault();
                // Better performance if there are tap events on document
                ev.stopPropagation();

                isScrollable = s.mode != 'clickpick';
                target = $('.dw-ul', this);
                setGlobals(target);
                moved = iv[index] !== undefined; // Don't allow tap, if still moving
                p = moved ? getCurrentPosition(target) : pos[index];
                start = getCoord(ev, 'Y', true);
                startTime = new Date();
                stop = start;
                scroll(target, index, p, 0.001);

                if (isScrollable) {
                    target.closest('.dwwl').addClass('dwa');
                }

                if (ev.type === 'mousedown') {
                    $(document).on('mousemove', onMove).on('mouseup', onEnd);
                }
            }
        }

        function onMove(ev) {
            if (target) {
                if (isScrollable) {
                    // Prevent scroll
                    ev.preventDefault();
                    ev.stopPropagation();
                    stop = getCoord(ev, 'Y', true);
                    if (Math.abs(stop - start) > 3 || moved) {
                        scroll(target, index, constrain(p + (start - stop) / itemHeight, min - 1, max + 1));
                        moved = true;
                    }
                }
            }
        }

        function onEnd(ev) {
            if (target) {
                var time = new Date() - startTime,
                    curr = constrain(Math.round(p + (start - stop) / itemHeight), min - 1, max + 1),
                    val = curr,
                    speed,
                    dist,
                    ttop = target.offset().top;

                // Better performance if there are tap events on document
                ev.stopPropagation();

                if (ev.type === 'mouseup') {
                    $(document).off('mousemove', onMove).off('mouseup', onEnd);
                }

                if (has3d && time < 300) {
                    speed = (stop - start) / time;
                    dist = (speed * speed) / s.speedUnit;
                    if (stop - start < 0) {
                        dist = -dist;
                    }
                } else {
                    dist = stop - start;
                }

                if (!moved) { // this is a "tap"
                    var idx = Math.floor((stop - ttop) / itemHeight),
                        li = $($('.dw-li', target)[idx]),
                        valid = li.hasClass('dw-v'),
                        hl = isScrollable;

                    time = 0.1;

                    if (trigger('onValueTap', [li]) !== false && valid) {
                        val = idx;
                    } else {
                        hl = true;
                    }

                    if (hl && valid) {
                        li.addClass('dw-hl'); // Highlight
                        setTimeout(function () {
                            li.removeClass('dw-hl');
                        }, 100);
                    }

                    if (!multiple && (s.confirmOnTap === true || s.confirmOnTap[index]) && li.hasClass('dw-sel')) {
                        that.select();
                        target = false;
                        return;
                    }
                } else {
                    val = constrain(Math.round(p - dist / itemHeight), min, max);
                    time = speed ? Math.max(0.1, Math.abs((val - curr) / speed) * s.timeUnit) : 0.1;
                }

                if (isScrollable) {
                    calc(target, index, val, 0, time, true);
                }

                target = false;
            }
        }

        function onBtnStart(ev) {
            btn = $(this);
            // +/- buttons
            if (testTouch(ev, this)) {
                step(ev, btn.closest('.dwwl'), btn.hasClass('dwwbp') ? plus : minus);
            }
            if (ev.type === 'mousedown') {
                $(document).on('mouseup', onBtnEnd);
            }
        }

        function onBtnEnd(ev) {
            btn = null;
            if (click) {
                clearInterval(timer);
                click = false;
            }
            if (ev.type === 'mouseup') {
                $(document).off('mouseup', onBtnEnd);
            }
        }

        function onKeyDown(ev) {
            if (ev.keyCode == 38) { // up
                step(ev, $(this), minus);
            } else if (ev.keyCode == 40) { // down
                step(ev, $(this), plus);
            }
        }

        function onKeyUp() {
            if (click) {
                clearInterval(timer);
                click = false;
            }
        }

        function onScroll(ev) {
            if (!isReadOnly(this)) {
                ev.preventDefault();
                ev = ev.originalEvent || ev;

                var delta = ev.deltaY || ev.wheelDelta || ev.detail,
                    t = $('.dw-ul', this);

                setGlobals(t);

                scroll(t, index, constrain(((delta < 0 ? -20 : 20) - pixels[index]) / itemHeight, min - 1, max + 1));

                clearTimeout(scrollDebounce);
                scrollDebounce = setTimeout(function () {
                    calc(t, index, Math.round(pos[index]), delta > 0 ? 1 : 2, 0.1);
                }, 200);
            }
        }

        // Private functions

        function step(ev, w, func) {
            ev.stopPropagation();
            ev.preventDefault();
            if (!click && !isReadOnly(w) && !w.hasClass('dwa')) {
                click = true;
                // + Button
                var t = w.find('.dw-ul');

                setGlobals(t);
                clearInterval(timer);
                timer = setInterval(function () {
                    func(t);
                }, s.delay);
                func(t);
            }
        }

        function isReadOnly(wh) {
            if ($.isArray(s.readonly)) {
                var i = $('.dwwl', $markup).index(wh);
                return s.readonly[i];
            }
            return s.readonly;
        }

        function generateWheelItems(i) {
            var html = '<div class="dw-bf">',
                w = wheels[i],
                l = 1,
                labels = w.labels || [],
                values = w.values || [],
                keys = w.keys || values;

            $.each(values, function (j, v) {
                if (l % 20 === 0) {
                    html += '</div><div class="dw-bf">';
                }
                html += '<div role="option" aria-selected="false" class="dw-li dw-v" data-val="' + keys[j] + '"' + (labels[j] ? ' aria-label="' + labels[j] + '"' : '') + ' style="height:' + itemHeight + 'px;line-height:' + itemHeight + 'px;">' +
                    '<div class="dw-i"' + (lines > 1 ? ' style="line-height:' + Math.round(itemHeight / lines) + 'px;font-size:' + Math.round(itemHeight / lines * 0.8) + 'px;"' : '') + '>' + v + '</div></div>';
                l++;
            });

            html += '</div>';
            return html;
        }

        function setGlobals(t) {
            multiple = t.closest('.dwwl').hasClass('dwwms');
            min = $('.dw-li', t).index($(multiple ? '.dw-li' : '.dw-v', t).eq(0));
            max = Math.max(min, $('.dw-li', t).index($(multiple ? '.dw-li' : '.dw-v', t).eq(-1)) - (multiple ? s.rows - (s.mode == 'scroller' ? 1 : 3) : 0));
            index = $('.dw-ul', $markup).index(t);
        }

        function formatHeader(v) {
            var t = s.headerText;
            return t ? (typeof t === 'function' ? t.call(el, v) : t.replace(/\{value\}/i, v)) : '';
        }

        function getCurrentPosition(t) {
            return Math.round(-util.getPosition(t, true) / itemHeight);
        }

        function ready(t, i) {
            clearTimeout(iv[i]);
            delete iv[i];
            t.closest('.dwwl').removeClass('dwa');
        }

        function scroll(t, index, val, time, active) {
            var px = -val * itemHeight,
                style = t[0].style;

            if (px == pixels[index] && iv[index]) {
                return;
            }

            //if (time && px != pixels[index]) {
            // Trigger animation start event
            //trigger('onAnimStart', [$markup, index, time]);
            //}

            pixels[index] = px;

            if (has3d) {
                style[pr + 'Transition'] = util.prefix + 'transform ' + (time ? time.toFixed(3) : 0) + 's ease-out';
                style[pr + 'Transform'] = 'translate3d(0,' + px + 'px,0)';
            } else {
                style.top = px + 'px';
            }

            if (iv[index]) {
                ready(t, index);
            }

            if (time && active) {
                t.closest('.dwwl').addClass('dwa');
                iv[index] = setTimeout(function () {
                    ready(t, index);
                }, time * 1000);
            }

            pos[index] = val;
        }

        function getValid(val, t, dir, multiple, select) {
            var selected,
                cell = $('.dw-li[data-val="' + val + '"]', t),
                cells = $('.dw-li', t),
                v = cells.index(cell),
                l = cells.length;

            if (multiple) {
                setGlobals(t);
            } else if (!cell.hasClass('dw-v')) { // Scroll to a valid cell
                var cell1 = cell,
                    cell2 = cell,
                    dist1 = 0,
                    dist2 = 0;

                while (v - dist1 >= 0 && !cell1.hasClass('dw-v')) {
                    dist1++;
                    cell1 = cells.eq(v - dist1);
                }

                while (v + dist2 < l && !cell2.hasClass('dw-v')) {
                    dist2++;
                    cell2 = cells.eq(v + dist2);
                }

                // If we have direction (+/- or mouse wheel), the distance does not count
                if (((dist2 < dist1 && dist2 && dir !== 2) || !dist1 || (v - dist1 < 0) || dir == 1) && cell2.hasClass('dw-v')) {
                    cell = cell2;
                    v = v + dist2;
                } else {
                    cell = cell1;
                    v = v - dist1;
                }
            }

            selected = cell.hasClass('dw-sel');

            if (select) {
                if (!multiple) {
                    $('.dw-sel', t).removeAttr('aria-selected');
                    cell.attr('aria-selected', 'true');
                }

                // Add selected class to cell
                $('.dw-sel', t).removeClass('dw-sel');
                cell.addClass('dw-sel');
            }

            return {
                selected: selected,
                v: multiple ? constrain(v, min, max) : v,
                val: cell.hasClass('dw-v') || multiple ? cell.attr('data-val') : null
            };
        }

        function scrollToPos(time, index, manual, dir, active) {
            // Call validation event
            if (trigger('validate', [$markup, index, time, dir]) !== false) {
                // Set scrollers to position
                $('.dw-ul', $markup).each(function (i) {
                    var t = $(this),
                        multiple = t.closest('.dwwl').hasClass('dwwms'),
                        sc = i == index || index === undefined,
                        res = getValid(that._tempWheelArray[i], t, dir, multiple, true),
                        selected = res.selected;

                    if (!selected || sc) {
                        // Set valid value
                        that._tempWheelArray[i] = res.val;

                        // Scroll to position
                        scroll(t, i, res.v, sc ? time : 0.1, sc ? active : false);
                    }
                });

                trigger('onValidated', [index]);

                // Reformat value if validation changed something
                that._tempValue = s.formatValue(that._tempWheelArray, that);

                if (that.live) {
                    that._hasValue = manual || that._hasValue;
                    setValue(manual, manual, 0, true);
                }

                that._header.html(formatHeader(that._tempValue));

                if (manual) {
                    trigger('onChange', [that._tempValue]);
                }
            }

        }

        function calc(t, idx, val, dir, time, active) {
            val = constrain(val, min, max);

            // Set selected scroller value
            that._tempWheelArray[idx] = $('.dw-li', t).eq(val).attr('data-val');

            scroll(t, idx, val, time, active);

            setTimeout(function () {
                // Validate
                scrollToPos(time, idx, true, dir, active);
            }, 10);
        }

        function plus(t) {
            var val = pos[index] + 1;
            calc(t, index, val > max ? min : val, 1, 0.1);
        }

        function minus(t) {
            var val = pos[index] - 1;
            calc(t, index, val < min ? max : val, 2, 0.1);
        }

        function setValue(fill, change, time, noscroll, temp) {
            if (that._isVisible && !noscroll) {
                scrollToPos(time);
            }

            that._tempValue = s.formatValue(that._tempWheelArray, that);

            if (!temp) {
                that._wheelArray = that._tempWheelArray.slice(0);
                that._value = that._hasValue ? that._tempValue : null;
            }

            if (fill) {

                trigger('onValueFill', [that._hasValue ? that._tempValue : '', change]);

                if (that._isInput) {
                    $elm.val(that._hasValue ? that._tempValue : '');
                }

                if (change) {
                    that._preventChange = true;
                    $elm.change();
                }
            }
        }

        // Call the parent constructor
        classes.Frame.call(this, el, settings, true);

        // Public functions

        /**
         * Gets the selected wheel values, formats it, and set the value of the scroller instance.
         * If input parameter is true, populates the associated input element.
         * @param {Array} values Wheel values.
         * @param {Boolean} [fill=false] Also set the value of the associated input element.
         * @param {Number} [time=0] Animation time
         * @param {Boolean} [temp=false] If true, then only set the temporary value.(only scroll there but not set the value)
         * @param {Boolean} [change=false] Trigger change on the input element
         */
        that.setVal = that._setVal = function (val, fill, change, temp, time) {
            that._hasValue = val !== null && val !== undefined;
            that._tempWheelArray = $.isArray(val) ? val.slice(0) : s.parseValue.call(el, val, that) || [];
            setValue(fill, change === undefined ? fill : change, time, false, temp);
        };

        /**
         * Returns the selected value
         */
        that.getVal = that._getVal = function (temp) {
            var val = that._hasValue || temp ? that[temp ? '_tempValue' : '_value'] : null;
            return util.isNumeric(val) ? +val : val;
        };

        /*
         * Sets the wheel values (passed as an array)
         */
        that.setArrayVal = that.setVal;

        /*
         * Returns the selected wheel values as an array
         */
        that.getArrayVal = function (temp) {
            return temp ? that._tempWheelArray : that._wheelArray;
        };

        // @deprecated since 2.14.0, backward compatibility code
        // ---

        that.setValue = function (val, fill, time, temp, change) {
            that.setVal(val, fill, change, temp, time);
        };

        /**
         * Return the selected wheel values.
         */
        that.getValue = that.getArrayVal;

        // ---

        /**
         * Changes the values of a wheel, and scrolls to the correct position
         * @param {Array} idx Indexes of the wheels to change.
         * @param {Number} [time=0] Animation time when scrolling to the selected value on the new wheel.
         * @param {Boolean} [manual=false] Indicates that the change was triggered by the user or from code.
         */
        that.changeWheel = function (idx, time, manual) {
            if ($markup) {
                var i = 0,
                    nr = idx.length;

                $.each(s.wheels, function (j, wg) {
                    $.each(wg, function (k, w) {
                        if ($.inArray(i, idx) > -1) {
                            wheels[i] = w;
                            $('.dw-ul', $markup).eq(i).html(generateWheelItems(i));
                            nr--;
                            if (!nr) {
                                that.position();
                                scrollToPos(time, undefined, manual);
                                return false;
                            }
                        }
                        i++;
                    });
                    if (!nr) {
                        return false;
                    }
                });
            }
        };

        /**
         * Returns the closest valid cell.
         */
        that.getValidCell = getValid;

        that.scroll = scroll;

        // Protected overrides

        that._generateContent = function () {
            var lbl,
                html = '',
                l = 0;

            $.each(s.wheels, function (i, wg) { // Wheel groups
                html += '<div class="mbsc-w-p dwc' + (s.mode != 'scroller' ? ' dwpm' : ' dwsc') + (s.showLabel ? '' : ' dwhl') + '">' +
                    '<div class="dwwc"' + (s.maxWidth ? '' : ' style="max-width:600px;"') + '>' +
                    (hasFlex ? '' : '<table class="dw-tbl" cellpadding="0" cellspacing="0"><tr>');

                $.each(wg, function (j, w) { // Wheels
                    wheels[l] = w;
                    lbl = w.label !== undefined ? w.label : j;
                    html += '<' + (hasFlex ? 'div' : 'td') + ' class="dwfl"' + ' style="' +
                        (s.fixedWidth ? ('width:' + (s.fixedWidth[l] || s.fixedWidth) + 'px;') :
                            (s.minWidth ? ('min-width:' + (s.minWidth[l] || s.minWidth) + 'px;') : 'min-width:' + s.width + 'px;') +
                            (s.maxWidth ? ('max-width:' + (s.maxWidth[l] || s.maxWidth) + 'px;') : '')) + '">' +
                        '<div class="dwwl dwwl' + l + (w.multiple ? ' dwwms' : '') + '">' +
                        (s.mode != 'scroller' ?
                            '<div class="dwb-e dwwb dwwbp ' + (s.btnPlusClass || '') + '" style="height:' + itemHeight + 'px;line-height:' + itemHeight + 'px;"><span>+</span></div>' + // + button
                            '<div class="dwb-e dwwb dwwbm ' + (s.btnMinusClass || '') + '" style="height:' + itemHeight + 'px;line-height:' + itemHeight + 'px;"><span>&ndash;</span></div>' : '') + // - button
                        '<div class="dwl">' + lbl + '</div>' + // Wheel label
                        '<div tabindex="0" aria-live="off" aria-label="' + lbl + '" role="listbox" class="dwww">' +
                        '<div class="dww" style="height:' + (s.rows * itemHeight) + 'px;">' +
                        '<div class="dw-ul" style="margin-top:' + (w.multiple ? (s.mode == 'scroller' ? 0 : itemHeight) : s.rows / 2 * itemHeight - itemHeight / 2) + 'px;">';

                    // Create wheel values
                    html += generateWheelItems(l) +
                        '</div></div><div class="dwwo"></div></div><div class="dwwol"' +
                        (s.selectedLineHeight ? ' style="height:' + itemHeight + 'px;margin-top:-' + (itemHeight / 2 + (s.selectedLineBorder || 0)) + 'px;"' : '') + '></div></div>' +
                        (hasFlex ? '</div>' : '</td>');

                    l++;
                });

                html += (hasFlex ? '' : '</tr></table>') + '</div></div>';
            });

            return html;
        };

        that._attachEvents = function ($markup) {
            $markup
                .on('keydown', '.dwwl', onKeyDown)
                .on('keyup', '.dwwl', onKeyUp)
                .on('touchstart mousedown', '.dwwl', onStart)
                .on('touchmove', '.dwwl', onMove)
                .on('touchend', '.dwwl', onEnd)
                .on('touchstart mousedown', '.dwwb', onBtnStart)
                .on('touchend touchcancel', '.dwwb', onBtnEnd);

            if (s.mousewheel) {
                $markup.on('wheel mousewheel', '.dwwl', onScroll);
            }
        };

        that._markupReady = function ($m) {
            $markup = $m;
            pixels = {};
            scrollToPos();
        };

        that._fillValue = function () {
            that._hasValue = true;
            setValue(true, true, 0, true);
        };

        that._readValue = function () {
            var v = $elm.val() || '';

            if (v !== '') {
                that._hasValue = true;
            }

            that._tempWheelArray = that._hasValue && that._wheelArray ? that._wheelArray.slice(0) : s.parseValue.call(el, v, that) || [];
            setValue();
        };

        that._processSettings = function () {
            s = that.settings;
            trigger = that.trigger;
            itemHeight = s.height;
            lines = s.multiline;

            that._isLiquid = (s.layout || (/top|bottom/.test(s.display) && s.wheels.length == 1 ? 'liquid' : '')) === 'liquid';

            // @deprecated since 2.15.0, backward compatibility code
            // ---
            if (s.formatResult) {
                s.formatValue = s.formatResult;
            }
            // ---

            if (lines > 1) {
                s.cssClass = (s.cssClass || '') + ' dw-ml';
            }

            // Ensure a minimum number of 3 items if clickpick buttons present
            if (s.mode != 'scroller') {
                s.rows = Math.max(3, s.rows);
            }
        };

        // Properties

        that._selectedValues = {};

        // Constructor
        if (!inherit) {
            that.init(settings);
        }
    };

    // Extend defaults
    classes.Scroller.prototype = {
        _hasDef: true,
        _hasTheme: true,
        _hasLang: true,
        _hasPreset: true,
        _class: 'scroller',
        _defaults: $.extend({}, classes.Frame.prototype._defaults, {
            // Options
            minWidth: 80,
            height: 40,
            rows: 3,
            multiline: 1,
            delay: 300,
            readonly: false,
            showLabel: true,
            confirmOnTap: true,
            wheels: [],
            mode: 'scroller',
            preset: '',
            speedUnit: 0.0012,
            timeUnit: 0.08,
            formatValue: function (d) {
                return d.join(' ');
            },
            parseValue: function (value, inst) {
                var val = [],
                    ret = [],
                    i = 0,
                    found,
                    keys;

                if (value !== null && value !== undefined) {
                    val = (value + '').split(' ');
                }

                $.each(inst.settings.wheels, function (j, wg) {
                    $.each(wg, function (k, w) {
                        keys = w.keys || w.values;
                        found = keys[0]; // Default to first wheel value if not found
                        $.each(keys, function (l, key) {
                            if (val[i] == key) { // Don't do strict comparison
                                found = key;
                                return false;
                            }
                        });
                        ret.push(found);
                        i++;
                    });
                });
                return ret;
            }
        })
    };

    ms.themes.scroller = ms.themes.frame;

})(jQuery, window, document);

;
(function ($, undefined) {
    var ms = $.mobiscroll;

    ms.datetime = {
        defaults: {
            shortYearCutoff: '+10',
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
            amText: 'am',
            pmText: 'pm',
            getYear: function (d) { return d.getFullYear(); },
            getMonth: function (d) { return d.getMonth(); },
            getDay: function (d) { return d.getDate(); },
            getDate: function (y, m, d, h, i, s, u) { return new Date(y, m, d, h || 0, i || 0, s || 0, u || 0); },
            getMaxDayOfMonth: function (y, m) { return 32 - new Date(y, m, 32).getDate(); },
            getWeekNumber: function (d) {
                // Copy date so don't modify original
                d = new Date(d);
                d.setHours(0, 0, 0);
                // Set to nearest Thursday: current date + 4 - current day number
                // Make Sunday's day number 7
                d.setDate(d.getDate() + 4 - (d.getDay() || 7));
                // Get first day of year
                var yearStart = new Date(d.getFullYear(), 0, 1);
                // Calculate full weeks to nearest Thursday
                return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
            }
        },
        /**
        * Format a date into a string value with a specified format.
        * @param {String} format Output format.
        * @param {Date} date Date to format.
        * @param {Object} [settings={}] Settings.
        * @return {String} Returns the formatted date string.
        */
        formatDate: function (format, date, settings) {
            if (!date) {
                return null;
            }
            var s = $.extend({}, ms.datetime.defaults, settings),
                look = function (m) { // Check whether a format character is doubled
                    var n = 0;
                    while (i + 1 < format.length && format.charAt(i + 1) == m) {
                        n++;
                        i++;
                    }
                    return n;
                },
                f1 = function (m, val, len) { // Format a number, with leading zero if necessary
                    var n = '' + val;
                    if (look(m)) {
                        while (n.length < len) {
                            n = '0' + n;
                        }
                    }
                    return n;
                },
                f2 = function (m, val, s, l) { // Format a name, short or long as requested
                    return (look(m) ? l[val] : s[val]);
                },
                i,
                year,
                output = '',
                literal = false;

            for (i = 0; i < format.length; i++) {
                if (literal) {
                    if (format.charAt(i) == "'" && !look("'")) {
                        literal = false;
                    } else {
                        output += format.charAt(i);
                    }
                } else {
                    switch (format.charAt(i)) {
                        case 'd':
                            output += f1('d', s.getDay(date), 2);
                            break;
                        case 'D':
                            output += f2('D', date.getDay(), s.dayNamesShort, s.dayNames);
                            break;
                        case 'o':
                            output += f1('o', (date.getTime() - new Date(date.getFullYear(), 0, 0).getTime()) / 86400000, 3);
                            break;
                        case 'm':
                            output += f1('m', s.getMonth(date) + 1, 2);
                            break;
                        case 'M':
                            output += f2('M', s.getMonth(date), s.monthNamesShort, s.monthNames);
                            break;
                        case 'y':
                            year = s.getYear(date);
                            output += (look('y') ? year : (year % 100 < 10 ? '0' : '') + year % 100);
                            //output += (look('y') ? date.getFullYear() : (date.getYear() % 100 < 10 ? '0' : '') + date.getYear() % 100);
                            break;
                        case 'h':
                            var h = date.getHours();
                            output += f1('h', (h > 12 ? (h - 12) : (h === 0 ? 12 : h)), 2);
                            break;
                        case 'H':
                            output += f1('H', date.getHours(), 2);
                            break;
                        case 'i':
                            output += f1('i', date.getMinutes(), 2);
                            break;
                        case 's':
                            output += f1('s', date.getSeconds(), 2);
                            break;
                        case 'a':
                            output += date.getHours() > 11 ? s.pmText : s.amText;
                            break;
                        case 'A':
                            output += date.getHours() > 11 ? s.pmText.toUpperCase() : s.amText.toUpperCase();
                            break;
                        case "'":
                            if (look("'")) {
                                output += "'";
                            } else {
                                literal = true;
                            }
                            break;
                        default:
                            output += format.charAt(i);
                    }
                }
            }
            return output;
        },
        /**
        * Extract a date from a string value with a specified format.
        * @param {String} format Input format.
        * @param {String} value String to parse.
        * @param {Object} [settings={}] Settings.
        * @return {Date} Returns the extracted date.
        */
        parseDate: function (format, value, settings) {
            var s = $.extend({}, ms.datetime.defaults, settings),
                def = s.defaultValue || new Date();

            if (!format || !value) {
                return def;
            }

            // If already a date object
            if (value.getTime) {
                return value;
            }

            value = (typeof value == 'object' ? value.toString() : value + '');

            var shortYearCutoff = s.shortYearCutoff,
                year = s.getYear(def),
                month = s.getMonth(def) + 1,
                day = s.getDay(def),
                doy = -1,
                hours = def.getHours(),
                minutes = def.getMinutes(),
                seconds = 0, //def.getSeconds(),
                ampm = -1,
                literal = false, // Check whether a format character is doubled
                lookAhead = function (match) {
                    var matches = (iFormat + 1 < format.length && format.charAt(iFormat + 1) == match);
                    if (matches) {
                        iFormat++;
                    }
                    return matches;
                },
                getNumber = function (match) { // Extract a number from the string value
                    lookAhead(match);
                    var size = (match == '@' ? 14 : (match == '!' ? 20 : (match == 'y' ? 4 : (match == 'o' ? 3 : 2)))),
                        digits = new RegExp('^\\d{1,' + size + '}'),
                        num = value.substr(iValue).match(digits);

                    if (!num) {
                        return 0;
                    }
                    iValue += num[0].length;
                    return parseInt(num[0], 10);
                },
                getName = function (match, s, l) { // Extract a name from the string value and convert to an index
                    var names = (lookAhead(match) ? l : s),
                        i;

                    for (i = 0; i < names.length; i++) {
                        if (value.substr(iValue, names[i].length).toLowerCase() == names[i].toLowerCase()) {
                            iValue += names[i].length;
                            return i + 1;
                        }
                    }
                    return 0;
                },
                checkLiteral = function () {
                    iValue++;
                },
                iValue = 0,
                iFormat;

            for (iFormat = 0; iFormat < format.length; iFormat++) {
                if (literal) {
                    if (format.charAt(iFormat) == "'" && !lookAhead("'")) {
                        literal = false;
                    } else {
                        checkLiteral();
                    }
                } else {
                    switch (format.charAt(iFormat)) {
                        case 'd':
                            day = getNumber('d');
                            break;
                        case 'D':
                            getName('D', s.dayNamesShort, s.dayNames);
                            break;
                        case 'o':
                            doy = getNumber('o');
                            break;
                        case 'm':
                            month = getNumber('m');
                            break;
                        case 'M':
                            month = getName('M', s.monthNamesShort, s.monthNames);
                            break;
                        case 'y':
                            year = getNumber('y');
                            break;
                        case 'H':
                            hours = getNumber('H');
                            break;
                        case 'h':
                            hours = getNumber('h');
                            break;
                        case 'i':
                            minutes = getNumber('i');
                            break;
                        case 's':
                            seconds = getNumber('s');
                            break;
                        case 'a':
                            ampm = getName('a', [s.amText, s.pmText], [s.amText, s.pmText]) - 1;
                            break;
                        case 'A':
                            ampm = getName('A', [s.amText, s.pmText], [s.amText, s.pmText]) - 1;
                            break;
                        case "'":
                            if (lookAhead("'")) {
                                checkLiteral();
                            } else {
                                literal = true;
                            }
                            break;
                        default:
                            checkLiteral();
                    }
                }
            }
            if (year < 100) {
                year += new Date().getFullYear() - new Date().getFullYear() % 100 +
                    (year <= (typeof shortYearCutoff != 'string' ? shortYearCutoff : new Date().getFullYear() % 100 + parseInt(shortYearCutoff, 10)) ? 0 : -100);
            }
            if (doy > -1) {
                month = 1;
                day = doy;
                do {
                    var dim = 32 - new Date(year, month - 1, 32).getDate();
                    if (day <= dim) {
                        break;
                    }
                    month++;
                    day -= dim;
                } while (true);
            }
            hours = (ampm == -1) ? hours : ((ampm && hours < 12) ? (hours + 12) : (!ampm && hours == 12 ? 0 : hours));

            var date = s.getDate(year, month - 1, day, hours, minutes, seconds);

            if (s.getYear(date) != year || s.getMonth(date) + 1 != month || s.getDay(date) != day) {
                return def; // Invalid date
            }

            return date;
        }
    };

    // @deprecated since 2.11.0, backward compatibility code
    // ---
    ms.formatDate = ms.datetime.formatDate;
    ms.parseDate = ms.datetime.parseDate;
    // ---
    
})(jQuery);
;
(function ($, undefined) {

    var ms = $.mobiscroll,
        datetime = ms.datetime,
        date = new Date(),
        defaults = {
            startYear: date.getFullYear() - 100,
            endYear: date.getFullYear() + 1,
            separator: ' ',
            // Localization
            dateFormat: 'mm/dd/yy',
            dateOrder: 'mmddy',
            timeWheels: 'hhiiA',
            timeFormat: 'hh:ii A',
            dayText: 'Day',
            monthText: 'Month',
            yearText: 'Year',
            hourText: 'Hours',
            minuteText: 'Minutes',
            ampmText: '&nbsp;',
            secText: 'Seconds',
            nowText: 'Now'
        },
        /**
         * @class Mobiscroll.datetime
         * @extends Mobiscroll
         * Mobiscroll Datetime component
         */
        preset = function (inst) {
            var that = $(this),
                html5def = {},
                format;
            // Force format for html5 date inputs (experimental)
            if (that.is('input')) {
                switch (that.attr('type')) {
                    case 'date':
                        format = 'yy-mm-dd';
                        break;
                    case 'datetime':
                        format = 'yy-mm-ddTHH:ii:ssZ';
                        break;
                    case 'datetime-local':
                        format = 'yy-mm-ddTHH:ii:ss';
                        break;
                    case 'month':
                        format = 'yy-mm';
                        html5def.dateOrder = 'mmyy';
                        break;
                    case 'time':
                        format = 'HH:ii:ss';
                        break;
                }
                // Check for min/max attributes
                var min = that.attr('min'),
                    max = that.attr('max');
                if (min) {
                    html5def.minDate = datetime.parseDate(format, min);
                }
                if (max) {
                    html5def.maxDate = datetime.parseDate(format, max);
                }
            }

            // Set year-month-day order
            var i,
                k,
                keys,
                values,
                wg,
                start,
                end,
                hasTime,
                mins,
                maxs,
                orig = $.extend({}, inst.settings),
                s = $.extend(inst.settings, ms.datetime.defaults, defaults, html5def, orig),
                offset = 0,
                validValues = [],
                wheels = [],
                ord = [],
                o = {},
                innerValues = {},
                f = {
                    y: getYear,
                    m: getMonth,
                    d: getDay,
                    h: getHour,
                    i: getMinute,
                    s: getSecond,
                    u: getMillisecond,
                    a: getAmPm
                },
                invalid = s.invalid,
                valid = s.valid,
                p = s.preset,
                dord = s.dateOrder,
                tord = s.timeWheels,
                regen = dord.match(/D/),
                ampm = tord.match(/a/i),
                hampm = tord.match(/h/),
                hformat = p == 'datetime' ? s.dateFormat + s.separator + s.timeFormat : p == 'time' ? s.timeFormat : s.dateFormat,
                defd = new Date(),
                steps = s.steps || {},
                stepH = steps.hour || s.stepHour || 1,
                stepM = steps.minute || s.stepMinute || 1,
                stepS = steps.second || s.stepSecond || 1,
                zeroBased = steps.zeroBased,
                mind = s.minDate || new Date(s.startYear, 0, 1),
                maxd = s.maxDate || new Date(s.endYear, 11, 31, 23, 59, 59),
                minH = zeroBased ? 0 : mind.getHours() % stepH,
                minM = zeroBased ? 0 : mind.getMinutes() % stepM,
                minS = zeroBased ? 0 : mind.getSeconds() % stepS,
                maxH = getMax(stepH, minH, (hampm ? 11 : 23)),
                maxM = getMax(stepM, minM, 59),
                maxS = getMax(stepM, minM, 59);

            format = format || hformat;

            if (p.match(/date/i)) {

                // Determine the order of year, month, day wheels
                $.each(['y', 'm', 'd'], function (j, v) {
                    i = dord.search(new RegExp(v, 'i'));
                    if (i > -1) {
                        ord.push({
                            o: i,
                            v: v
                        });
                    }
                });
                ord.sort(function (a, b) {
                    return a.o > b.o ? 1 : -1;
                });
                $.each(ord, function (i, v) {
                    o[v.v] = i;
                });

                wg = [];
                for (k = 0; k < 3; k++) {
                    if (k == o.y) {
                        offset++;
                        values = [];
                        keys = [];
                        start = s.getYear(mind);
                        end = s.getYear(maxd);
                        for (i = start; i <= end; i++) {
                            keys.push(i);
                            values.push((dord.match(/yy/i) ? i : (i + '').substr(2, 2)) + (s.yearSuffix || ''));
                        }
                        addWheel(wg, keys, values, s.yearText);
                    } else if (k == o.m) {
                        offset++;
                        values = [];
                        keys = [];
                        for (i = 0; i < 12; i++) {
                            var str = dord.replace(/[dy]/gi, '').replace(/mm/, (i < 9 ? '0' + (i + 1) : i + 1) + (s.monthSuffix || '')).replace(/m/, i + 1 + (s.monthSuffix || ''));
                            keys.push(i);
                            values.push(str.match(/MM/) ? str.replace(/MM/, '<span class="dw-mon">' + s.monthNames[i] + '</span>') : str.replace(/M/, '<span class="dw-mon">' + s.monthNamesShort[i] + '</span>'));
                        }
                        addWheel(wg, keys, values, s.monthText);
                    } else if (k == o.d) {
                        offset++;
                        values = [];
                        keys = [];
                        for (i = 1; i < 32; i++) {
                            keys.push(i);
                            values.push((dord.match(/dd/i) && i < 10 ? '0' + i : i) + (s.daySuffix || ''));
                        }
                        addWheel(wg, keys, values, s.dayText);
                    }
                }
                wheels.push(wg);
            }

            if (p.match(/time/i)) {
                hasTime = true;

                // Determine the order of hours, minutes, seconds wheels
                ord = [];
                $.each(['h', 'i', 's', 'a'], function (i, v) {
                    i = tord.search(new RegExp(v, 'i'));
                    if (i > -1) {
                        ord.push({
                            o: i,
                            v: v
                        });
                    }
                });
                ord.sort(function (a, b) {
                    return a.o > b.o ? 1 : -1;
                });
                $.each(ord, function (i, v) {
                    o[v.v] = offset + i;
                });

                wg = [];
                for (k = offset; k < offset + 4; k++) {
                    if (k == o.h) {
                        offset++;
                        values = [];
                        keys = [];
                        for (i = minH; i < (hampm ? 12 : 24); i += stepH) {
                            keys.push(i);
                            values.push(hampm && i === 0 ? 12 : tord.match(/hh/i) && i < 10 ? '0' + i : i);
                        }
                        addWheel(wg, keys, values, s.hourText);
                    } else if (k == o.i) {
                        offset++;
                        values = [];
                        keys = [];
                        for (i = minM; i < 60; i += stepM) {
                            keys.push(i);
                            values.push(tord.match(/ii/) && i < 10 ? '0' + i : i);
                        }
                        addWheel(wg, keys, values, s.minuteText);
                    } else if (k == o.s) {
                        offset++;
                        values = [];
                        keys = [];
                        for (i = minS; i < 60; i += stepS) {
                            keys.push(i);
                            values.push(tord.match(/ss/) && i < 10 ? '0' + i : i);
                        }
                        addWheel(wg, keys, values, s.secText);
                    } else if (k == o.a) {
                        offset++;
                        var upper = tord.match(/A/);
                        addWheel(wg, [0, 1], upper ? [s.amText.toUpperCase(), s.pmText.toUpperCase()] : [s.amText, s.pmText], s.ampmText);
                    }
                }

                wheels.push(wg);
            }

            function get(d, i, def) {
                if (o[i] !== undefined) {
                    return +d[o[i]];
                }
                if (innerValues[i] !== undefined) {
                    return innerValues[i];
                }
                if (def !== undefined) {
                    return def;
                }
                return f[i](defd);
            }

            function addWheel(wg, k, v, lbl) {
                wg.push({
                    values: v,
                    keys: k,
                    label: lbl
                });
            }

            function step(v, st, min, max) {
                return Math.min(max, Math.floor(v / st) * st + min);
            }

            function getYear(d) {
                return s.getYear(d);
            }

            function getMonth(d) {
                return s.getMonth(d);
            }

            function getDay(d) {
                return s.getDay(d);
            }

            function getHour(d) {
                var hour = d.getHours();
                hour = hampm && hour >= 12 ? hour - 12 : hour;
                return step(hour, stepH, minH, maxH);
            }

            function getMinute(d) {
                return step(d.getMinutes(), stepM, minM, maxM);
            }

            function getSecond(d) {
                return step(d.getSeconds(), stepS, minS, maxS);
            }

            function getMillisecond(d) {
                return d.getMilliseconds();
            }

            function getAmPm(d) {
                return ampm && d.getHours() > 11 ? 1 : 0;
            }

            function getDate(d) {
                if (d === null) {
                    return d;
                }

                var year = get(d, 'y'),
                    month = get(d, 'm'),
                    day = Math.min(get(d, 'd'), s.getMaxDayOfMonth(year, month)),
                    hour = get(d, 'h', 0);

                return s.getDate(year, month, day, get(d, 'a', 0) ? hour + 12 : hour, get(d, 'i', 0), get(d, 's', 0), get(d, 'u', 0));
            }

            function getMax(step, min, max) {
                return Math.floor((max - min) / step) * step + min;
            }

            function getClosestValidDate(d, dir) {
                var next,
                    prev,
                    nextValid = false,
                    prevValid = false,
                    up = 0,
                    down = 0;

                // Normalize min and max dates for comparing later (set default values where there are no values from wheels)
                mind = getDate(getArray(mind));
                maxd = getDate(getArray(maxd));

                if (isValid(d)) {
                    return d;
                }

                if (d < mind) {
                    d = mind;
                }

                if (d > maxd) {
                    d = maxd;
                }

                next = d;
                prev = d;

                if (dir !== 2) {
                    nextValid = isValid(next);

                    while (!nextValid && next < maxd) {
                        next = new Date(next.getTime() + 1000 * 60 * 60 * 24);
                        nextValid = isValid(next);
                        up++;
                    }
                }

                if (dir !== 1) {
                    prevValid = isValid(prev);

                    while (!prevValid && prev > mind) {
                        prev = new Date(prev.getTime() - 1000 * 60 * 60 * 24);
                        prevValid = isValid(prev);
                        down++;
                    }
                }

                if (dir === 1 && nextValid) {
                    return next;
                }

                if (dir === 2 && prevValid) {
                    return prev;
                }

                return down <= up && prevValid ? prev : next;
            }

            function isValid(d) {
                if (d < mind) {
                    return false;
                }

                if (d > maxd) {
                    return false;
                }

                if (isInObj(d, valid)) {
                    return true;
                }

                if (isInObj(d, invalid)) {
                    return false;
                }

                return true;
            }

            function isInObj(d, obj) {
                var curr,
                    j,
                    v;

                if (obj) {
                    for (j = 0; j < obj.length; j++) {
                        curr = obj[j];
                        v = curr + '';
                        if (!curr.start) {
                            if (curr.getTime) { // Exact date
                                if (d.getFullYear() == curr.getFullYear() && d.getMonth() == curr.getMonth() && d.getDate() == curr.getDate()) {
                                    return true;
                                }
                            } else if (!v.match(/w/i)) { // Day of month
                                v = v.split('/');
                                if (v[1]) {
                                    if ((v[0] - 1) == d.getMonth() && v[1] == d.getDate()) {
                                        return true;
                                    }
                                } else if (v[0] == d.getDate()) {
                                    return true;
                                }
                            } else { // Day of week
                                v = +v.replace('w', '');
                                if (v == d.getDay()) {
                                    return true;
                                }
                            }
                        }
                    }
                }
                return false;
            }

            function validateDates(obj, y, m, first, maxdays, idx, val) {
                var j, d, v;

                if (obj) {
                    for (j = 0; j < obj.length; j++) {
                        d = obj[j];
                        v = d + '';
                        if (!d.start) {
                            if (d.getTime) { // Exact date
                                if (s.getYear(d) == y && s.getMonth(d) == m) {
                                    idx[s.getDay(d) - 1] = val;
                                }
                            } else if (!v.match(/w/i)) { // Day of month
                                v = v.split('/');
                                if (v[1]) {
                                    if (v[0] - 1 == m) {
                                        idx[v[1] - 1] = val;
                                    }
                                } else {
                                    idx[v[0] - 1] = val;
                                }
                            } else { // Day of week
                                v = +v.replace('w', '');
                                for (k = v - first; k < maxdays; k += 7) {
                                    if (k >= 0) {
                                        idx[k] = val;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            function validateTimes(vobj, i, v, temp, y, m, d, target, valid) {
                var dd, ss, str, parts1, parts2, prop1, prop2, v1, v2, j, i1, i2, add, remove, all, hours1, hours2, hours3,
                    spec = {},
                    steps = {
                        h: stepH,
                        i: stepM,
                        s: stepS,
                        a: 1
                    },
                    day = s.getDate(y, m, d),
                    w = ['a', 'h', 'i', 's'];

                if (vobj) {
                    $.each(vobj, function (i, obj) {
                        if (obj.start) {
                            obj.apply = false;
                            dd = obj.d;
                            ss = dd + '';
                            str = ss.split('/');
                            if (dd && ((dd.getTime && y == s.getYear(dd) && m == s.getMonth(dd) && d == s.getDay(dd)) || // Exact date
                                    (!ss.match(/w/i) && ((str[1] && d == str[1] && m == str[0] - 1) || (!str[1] && d == str[0]))) || // Day of month
                                    (ss.match(/w/i) && day.getDay() == +ss.replace('w', '')) // Day of week
                                )) {
                                obj.apply = true;
                                spec[day] = true; // Prevent applying generic rule on day, if specific exists
                            }
                        }
                    });

                    $.each(vobj, function (x, obj) {
                        add = 0;
                        remove = 0;
                        i1 = 0;
                        i2 = undefined;
                        prop1 = true;
                        prop2 = true;
                        all = false;

                        if (obj.start && (obj.apply || (!obj.d && !spec[day]))) {

                            // Define time parts
                            parts1 = obj.start.split(':');
                            parts2 = obj.end.split(':');

                            for (j = 0; j < 3; j++) {
                                if (parts1[j] === undefined) {
                                    parts1[j] = 0;
                                }
                                if (parts2[j] === undefined) {
                                    parts2[j] = 59;
                                }
                                parts1[j] = +parts1[j];
                                parts2[j] = +parts2[j];
                            }

                            parts1.unshift(parts1[0] > 11 ? 1 : 0);
                            parts2.unshift(parts2[0] > 11 ? 1 : 0);

                            if (hampm) {
                                if (parts1[1] >= 12) {
                                    parts1[1] = parts1[1] - 12;
                                }

                                if (parts2[1] >= 12) {
                                    parts2[1] = parts2[1] - 12;
                                }
                            }

                            // Look behind
                            for (j = 0; j < i; j++) {
                                if (validValues[j] !== undefined) {
                                    v1 = step(parts1[j], steps[w[j]], mins[w[j]], maxs[w[j]]);
                                    v2 = step(parts2[j], steps[w[j]], mins[w[j]], maxs[w[j]]);
                                    hours1 = 0;
                                    hours2 = 0;
                                    hours3 = 0;
                                    if (hampm && j == 1) {
                                        hours1 = parts1[0] ? 12 : 0;
                                        hours2 = parts2[0] ? 12 : 0;
                                        hours3 = validValues[0] ? 12 : 0;
                                    }
                                    if (!prop1) {
                                        v1 = 0;
                                    }
                                    if (!prop2) {
                                        v2 = maxs[w[j]];
                                    }
                                    if ((prop1 || prop2) && (v1 + hours1 < validValues[j] + hours3 && validValues[j] + hours3 < v2 + hours2)) {
                                        all = true;
                                    }
                                    if (validValues[j] != v1) {
                                        prop1 = false;
                                    }
                                    if (validValues[j] != v2) {
                                        prop2 = false;
                                    }
                                }
                            }

                            // Look ahead
                            if (!valid) {
                                for (j = i + 1; j < 4; j++) {
                                    if (parts1[j] > 0) {
                                        add = steps[v];
                                    }
                                    if (parts2[j] < maxs[w[j]]) {
                                        remove = steps[v];
                                    }
                                }
                            }

                            if (!all) {
                                // Calculate min and max values
                                v1 = step(parts1[i], steps[v], mins[v], maxs[v]) + add;
                                v2 = step(parts2[i], steps[v], mins[v], maxs[v]) - remove;

                                if (prop1) {
                                    i1 = getValidIndex(target, v1, maxs[v], 0);
                                }

                                if (prop2) {
                                    i2 = getValidIndex(target, v2, maxs[v], 1);
                                }
                            }

                            // Disable values
                            if (prop1 || prop2 || all) {
                                if (valid) {
                                    $('.dw-li', target).slice(i1, i2).addClass('dw-v');
                                } else {
                                    $('.dw-li', target).slice(i1, i2).removeClass('dw-v');
                                }
                            }

                        }
                    });
                }
            }

            function getIndex(t, v) {
                return $('.dw-li', t).index($('.dw-li[data-val="' + v + '"]', t));
            }

            function getValidIndex(t, v, max, add) {
                if (v < 0) {
                    return 0;
                }
                if (v > max) {
                    return $('.dw-li', t).length;
                }
                return getIndex(t, v) + add;
            }

            function getArray(d, fillInner) {
                var ret = [];

                if (d === null || d === undefined) {
                    return d;
                }

                $.each(['y', 'm', 'd', 'a', 'h', 'i', 's', 'u'], function (x, i) {
                    if (o[i] !== undefined) {
                        ret[o[i]] = f[i](d);
                    }
                    if (fillInner) {
                        innerValues[i] = f[i](d);
                    }
                });

                return ret;
            }

            function convertRanges(arr) {
                var i, v, start,
                    ret = [];

                if (arr) {
                    for (i = 0; i < arr.length; i++) {
                        v = arr[i];
                        if (v.start && v.start.getTime) {
                            start = new Date(v.start);
                            while (start <= v.end) {
                                ret.push(new Date(start.getFullYear(), start.getMonth(), start.getDate()));
                                start.setDate(start.getDate() + 1);
                            }
                        } else {
                            ret.push(v);
                        }
                    }
                    return ret;
                }
                return arr;
            }

            // Extended methods
            // ---

            inst.getVal = function (temp) {
                return inst._hasValue || temp ? getDate(inst.getArrayVal(temp)) : null;
            };

            /**
             * Sets the selected date
             *
             * @param {Date} d Date to select.
             * @param {Boolean} [fill=false] Also set the value of the associated input element. Default is true.
             * @param {Number} [time=0] Animation time to scroll to the selected date.
             * @param {Boolean} [temp=false] Set temporary value only.
             * @param {Boolean} [change=fill] Trigger change on input element.
             */
            inst.setDate = function (d, fill, time, temp, change) {
                inst.setArrayVal(getArray(d), fill, change, temp, time);
            };

            /**
             * Returns the selected date.
             *
             * @param {Boolean} [temp=false] If true, return the currently shown date on the picker, otherwise the last selected one.
             * @return {Date}
             */
            inst.getDate = inst.getVal;

            // ---


            // Initializations
            // --- 

            inst.format = hformat;
            inst.order = o;

            inst.handlers.now = function () {
                inst.setDate(new Date(), false, 0.3, true, true);
            };

            inst.buttons.now = {
                text: s.nowText,
                handler: 'now'
            };

            invalid = convertRanges(invalid);
            valid = convertRanges(valid);

            mins = {
                y: mind.getFullYear(),
                m: 0,
                d: 1,
                h: minH,
                i: minM,
                s: minS,
                a: 0
            };
            maxs = {
                y: maxd.getFullYear(),
                m: 11,
                d: 31,
                h: maxH,
                i: maxM,
                s: maxS,
                a: 1
            };

            // ---

            return {
                wheels: wheels,
                headerText: s.headerText ? function () {
                    return datetime.formatDate(hformat, getDate(inst.getArrayVal(true)), s);
                } : false,
                formatValue: function (d) {
                    return datetime.formatDate(format, getDate(d), s);
                },
                parseValue: function (val) {
                    if (!val) {
                        innerValues = {};
                    }
                    return getArray(val ? datetime.parseDate(format, val, s) : (s.defaultValue || new Date()), !!val && !!val.getTime);
                },
                validate: function (dw, i, time, dir) {
                    var validated = getClosestValidDate(getDate(inst.getArrayVal(true)), dir),
                        temp = getArray(validated),
                        y = get(temp, 'y'),
                        m = get(temp, 'm'),
                        minprop = true,
                        maxprop = true;

                    $.each(['y', 'm', 'd', 'a', 'h', 'i', 's'], function (x, i) {
                        if (o[i] !== undefined) {
                            var min = mins[i],
                                max = maxs[i],
                                maxdays = 31,
                                val = get(temp, i),
                                t = $('.dw-ul', dw).eq(o[i]);

                            if (i == 'd') {
                                maxdays = s.getMaxDayOfMonth(y, m);
                                max = maxdays;
                                if (regen) {
                                    $('.dw-li', t).each(function () {
                                        var that = $(this),
                                            d = that.data('val'),
                                            w = s.getDate(y, m, d).getDay(),
                                            str = dord.replace(/[my]/gi, '').replace(/dd/, (d < 10 ? '0' + d : d) + (s.daySuffix || '')).replace(/d/, d + (s.daySuffix || ''));
                                        $('.dw-i', that).html(str.match(/DD/) ? str.replace(/DD/, '<span class="dw-day">' + s.dayNames[w] + '</span>') : str.replace(/D/, '<span class="dw-day">' + s.dayNamesShort[w] + '</span>'));
                                    });
                                }
                            }
                            if (minprop && mind) {
                                min = f[i](mind);
                            }
                            if (maxprop && maxd) {
                                max = f[i](maxd);
                            }
                            if (i != 'y') {
                                var i1 = getIndex(t, min),
                                    i2 = getIndex(t, max);
                                $('.dw-li', t).removeClass('dw-v').slice(i1, i2 + 1).addClass('dw-v');
                                if (i == 'd') { // Hide days not in month
                                    $('.dw-li', t).removeClass('dw-h').slice(maxdays).addClass('dw-h');
                                }
                            }
                            if (val < min) {
                                val = min;
                            }
                            if (val > max) {
                                val = max;
                            }
                            if (minprop) {
                                minprop = val == min;
                            }
                            if (maxprop) {
                                maxprop = val == max;
                            }
                            // Disable some days
                            if (i == 'd') {
                                var first = s.getDate(y, m, 1).getDay(),
                                    idx = {};

                                // Set invalid indexes
                                validateDates(invalid, y, m, first, maxdays, idx, 1);
                                // Delete indexes which are valid 
                                validateDates(valid, y, m, first, maxdays, idx, 0);

                                $.each(idx, function (i, v) {
                                    if (v) {
                                        $('.dw-li', t).eq(i).removeClass('dw-v');
                                    }
                                });
                            }
                        }
                    });

                    // Invalid times
                    if (hasTime) {
                        $.each(['a', 'h', 'i', 's'], function (i, v) {
                            var val = get(temp, v),
                                d = get(temp, 'd'),
                                t = $('.dw-ul', dw).eq(o[v]);

                            if (o[v] !== undefined) {
                                validateTimes(invalid, i, v, temp, y, m, d, t, 0);
                                validateTimes(valid, i, v, temp, y, m, d, t, 1);

                                // Get valid value
                                validValues[i] = +inst.getValidCell(val, t, dir).val;
                            }
                        });
                    }

                    inst._tempWheelArray = temp;
                }
            };
        };

    $.each(['date', 'time', 'datetime'], function (i, v) {
        ms.presets.scroller[v] = preset;
    });

})(jQuery);

;
(function ($) {

    $.each(['date', 'time', 'datetime'], function (i, v) {
        $.mobiscroll.presetShort(v);
    });

})(jQuery);

;
(function ($, undefined) {
    var ms = $.mobiscroll,
        util = ms.util,
        isString = util.isString,
        defaults = {
            batch: 40,
            inputClass: '',
            invalid: [],
            rtl: false,
            showInput: true,
            groupLabel: 'Groups',
            checkIcon: 'checkmark',
            dataText: 'text',
            dataValue: 'value',
            dataGroup: 'group',
            dataDisabled: 'disabled'
        };

    ms.presetShort('select');

    ms.presets.scroller.select = function (inst) {
        var change,
            defaultValue,
            group,
            groupArray,
            groupChanged,
            groupTap,
            groupWheelIdx,
            i,
            input,
            optionArray,
            optionWheelIdx,
            option,
            origValues,
            prevGroup,
            timer,
            batchChanged = {},
            batchStart = {},
            batchEnd = {},
            tempBatchStart = {},
            tempBatchEnd = {},
            orig = $.extend({}, inst.settings),
            s = $.extend(inst.settings, defaults, orig),
            batch = s.batch,
            layout = s.layout || (/top|bottom/.test(s.display) ? 'liquid' : ''),
            isLiquid = layout == 'liquid',
            elm = $(this),
            multiple = s.multiple || elm.prop('multiple'),
            maxSelect = util.isNumeric(s.multiple) ? s.multiple : Infinity,
            id = this.id + '_dummy',
            lbl = $('label[for="' + this.id + '"]').attr('for', id),
            label = s.label !== undefined ? s.label : (lbl.length ? lbl.text() : elm.attr('name')),
            selectedClass = 'dw-msel mbsc-ic mbsc-ic-' + s.checkIcon,
            origReadOnly = s.readonly,
            data = s.data,
            hasData = !!data,
            hasGroups = hasData ? !!s.group : $('optgroup', elm).length,
            groupSetup = s.group,
            groupWheel = hasGroups && groupSetup && groupSetup.groupWheel !== false,
            groupSep = hasGroups && groupSetup && groupWheel && groupSetup.clustered === true,
            groupHdr = hasGroups && (!groupSetup || (groupSetup.header !== false && !groupSep)),
            values = elm.val() || [],
            invalid = [],
            selectedValues = {},
            options = {},
            groups = {};

        function prepareData() {
            var gr,
                lbl,
                opt,
                txt,
                val,
                l = 0,
                c = 0,
                groupIndexes = {};

            options = {};
            groups = {};

            optionArray = [];
            groupArray = [];

            // Reset invalids
            invalid.length = 0;

            if (hasData) {
                $.each(s.data, function (i, v) {
                    txt = v[s.dataText];
                    val = v[s.dataValue];
                    lbl = v[s.dataGroup];
                    opt = {
                        value: val,
                        text: txt,
                        index: i
                    };
                    options[val] = opt;
                    optionArray.push(opt);

                    if (hasGroups) {
                        if (groupIndexes[lbl] === undefined) {
                            gr = {
                                text: lbl,
                                value: c,
                                options: [],
                                index: c
                            };
                            groups[c] = gr;
                            groupIndexes[lbl] = c;
                            groupArray.push(gr);
                            c++;
                        } else {
                            gr = groups[groupIndexes[lbl]];
                        }
                        if (groupSep) {
                            opt.index = gr.options.length;
                        }
                        opt.group = groupIndexes[lbl];
                        gr.options.push(opt);
                    }
                    if (v[s.dataDisabled]) {
                        invalid.push(val);
                    }
                });
            } else {
                if (hasGroups) {
                    $('optgroup', elm).each(function (i) {
                        groups[i] = {
                            text: this.label,
                            value: i,
                            options: [],
                            index: i
                        };
                        groupArray.push(groups[i]);
                        $('option', this).each(function (j) {
                            opt = {
                                value: this.value,
                                text: this.text,
                                index: groupSep ? j : l++,
                                group: i
                            };
                            options[this.value] = opt;
                            optionArray.push(opt);
                            groups[i].options.push(opt);
                            if (this.disabled) {
                                invalid.push(this.value);
                            }
                        });
                    });
                } else {
                    $('option', elm).each(function (i) {
                        opt = {
                            value: this.value,
                            text: this.text,
                            index: i
                        };
                        options[this.value] = opt;
                        optionArray.push(opt);
                        if (this.disabled) {
                            invalid.push(this.value);
                        }
                    });
                }
            }

            if (optionArray.length) {
                defaultValue = optionArray[0].value;
            }

            if (groupHdr) {
                optionArray = [];
                l = 0;
                $.each(groups, function (i, gr) {
                    val = '__group' + i;
                    opt = {
                        text: gr.text,
                        value: val,
                        group: i,
                        index: l++
                    };
                    options[val] = opt;
                    optionArray.push(opt);
                    invalid.push(opt.value);
                    $.each(gr.options, function (j, opt) {
                        opt.index = l++;
                        optionArray.push(opt);
                    });
                });
            }
        }

        function genValues(w, data, dataMap, value, index, multiple, label) {
            var i,
                wheel,
                keys = [],
                values = [],
                selectedIndex = dataMap[value] !== undefined ? dataMap[value].index : 0,
                start = Math.max(0, selectedIndex - batch),
                end = Math.min(data.length - 1, start + batch * 2);

            if (batchStart[index] !== start || batchEnd[index] !== end) {
                for (i = start; i <= end; i++) {
                    values.push(data[i].text);
                    keys.push(data[i].value);
                }
                batchChanged[index] = true;
                tempBatchStart[index] = start;
                tempBatchEnd[index] = end;

                wheel = {
                    multiple: multiple,
                    values: values,
                    keys: keys,
                    label: label
                };

                if (isLiquid) {
                    w[0][index] = wheel;
                } else {
                    w[index] = [wheel];
                }
            } else {
                batchChanged[index] = false;
            }
        }

        function genGroupWheel(w) {
            genValues(w, groupArray, groups, group, groupWheelIdx, false, s.groupLabel);
        }

        function genOptWheel(w) {
            genValues(w, groupSep ? groups[group].options : optionArray, options, option, optionWheelIdx, multiple, label);
        }

        function genWheels() {
            var w = [
                []
            ];

            if (groupWheel) {
                genGroupWheel(w);
            }

            genOptWheel(w);

            return w;
        }

        function getOption(v) {
            if (multiple) {
                if (v && isString(v)) {
                    v = v.split(',');
                }
                if ($.isArray(v)) {
                    v = v[0];
                }
            }

            option = v === undefined || v === null || v === '' || !options[v] ? defaultValue : v;

            if (groupWheel) {
                group = options[option] ? options[option].group : null;
                prevGroup = group;
            }
        }

        function getVal(temp, group) {
            var val = temp ? inst._tempWheelArray : (inst._hasValue ? inst._wheelArray : null);
            return val ? (s.group && group ? val : val[optionWheelIdx]) : null;
        }

        function onFill() {
            var txt,
                val,
                sel = [],
                i = 0;

            if (multiple) {
                val = [];

                for (i in selectedValues) {
                    sel.push(options[i] ? options[i].text : '');
                    val.push(i);
                }

                txt = sel.join(', ');
            } else {
                val = option;
                txt = options[option] ? options[option].text : '';
            }

            inst._tempValue = val;

            input.val(txt);
            elm.val(val);
        }

        function onTap(li) {
            var val = li.attr('data-val'),
                selected = li.hasClass('dw-msel');

            if (multiple && li.closest('.dwwl').hasClass('dwwms')) {
                if (li.hasClass('dw-v')) {
                    if (selected) {
                        li.removeClass(selectedClass).removeAttr('aria-selected');
                        delete selectedValues[val];
                    } else if (util.objectToArray(selectedValues).length < maxSelect) {
                        li.addClass(selectedClass).attr('aria-selected', 'true');
                        selectedValues[val] = val;
                    }
                }
                return false;
            } else if (li.hasClass('dw-w-gr')) {
                groupTap = li.attr('data-val');
            }
        }

        if (!s.invalid.length) {
            s.invalid = invalid;
        }

        if (groupWheel) {
            groupWheelIdx = 0;
            optionWheelIdx = 1;
        } else {
            groupWheelIdx = -1;
            optionWheelIdx = 0;
        }

        if (multiple) {
            elm.prop('multiple', true);

            if (values && isString(values)) {
                values = values.split(',');
            }
            for (i = 0; i < values.length; i++) {
                selectedValues[values[i]] = values[i];
            }
        }

        prepareData();

        getOption(elm.val());

        $('#' + id).remove();

        if (elm.next().is('input.mbsc-control')) {
            input = elm.off('.mbsc-form').next().removeAttr('tabindex');
        } else {
            input = $('<input type="text" id="' + id + '" class="mbsc-control mbsc-control-ev ' + s.inputClass + '" readonly />');

            if (s.showInput) {
                input.insertBefore(elm);
            }
        }

        inst.attachShow(input.attr('placeholder', s.placeholder || ''));

        elm.addClass('dw-hsel').attr('tabindex', -1).closest('.ui-field-contain').trigger('create');

        onFill();

        // Extended methods
        // ---

        inst.setVal = function (val, fill, change, temp, time) {
            if (multiple) {
                if (val && isString(val)) {
                    val = val.split(',');
                }
                selectedValues = util.arrayToObject(val);
                val = val ? val[0] : null;
            }
            inst._setVal(val, fill, change, temp, time);
        };

        inst.getVal = function (temp, group) {
            if (multiple) {
                return util.objectToArray(selectedValues);
            }
            return getVal(temp, group);
        };

        inst.refresh = function () {
            prepareData();

            batchStart = {};
            batchEnd = {};

            s.wheels = genWheels();

            batchStart[groupWheelIdx] = tempBatchStart[groupWheelIdx];
            batchEnd[groupWheelIdx] = tempBatchEnd[groupWheelIdx];
            batchStart[optionWheelIdx] = tempBatchStart[optionWheelIdx];
            batchEnd[optionWheelIdx] = tempBatchEnd[optionWheelIdx];

            // Prevent wheel generation on initial validation
            change = true;

            getOption(option);

            inst._tempWheelArray = groupWheel ? [group, option] : [option];

            if (inst._isVisible) {
                inst.changeWheel(groupWheel ? [groupWheelIdx, optionWheelIdx] : [optionWheelIdx]);
            }
        };

        // @deprecated since 2.14.0, backward compatibility code
        // ---
        inst.getValues = inst.getVal;

        inst.getValue = getVal;
        // ---

        // ---

        return {
            width: 50,
            layout: layout,
            headerText: false,
            anchor: input,
            confirmOnTap: groupWheel ? [false, true] : true,
            formatValue: function (d) {
                var i,
                    opt,
                    sel = [];

                if (multiple) {
                    for (i in selectedValues) {
                        sel.push(options[i] ? options[i].text : '');
                    }
                    return sel.join(', ');
                }

                opt = d[optionWheelIdx];

                return options[opt] ? options[opt].text : '';
            },
            parseValue: function (val) {
                getOption(val === undefined ? elm.val() : val);
                return groupWheel ? [group, option] : [option];
            },
            onValueTap: onTap,
            onValueFill: onFill,
            onBeforeShow: function () {
                if (multiple && s.counter) {
                    s.headerText = function () {
                        var length = 0;
                        $.each(selectedValues, function () {
                            length++;
                        });
                        return (length > 1 ? s.selectedPluralText || s.selectedText : s.selectedText).replace(/{count}/, length);
                    };
                }

                getOption(elm.val());


                if (groupWheel) {
                    inst._tempWheelArray = [group, option];
                }

                inst.refresh();
            },
            onMarkupReady: function (dw) {
                dw.addClass('dw-select');

                $('.dwwl' + groupWheelIdx, dw).on('mousedown touchstart', function () {
                    clearTimeout(timer);
                });

                $('.dwwl' + optionWheelIdx, dw).on('mousedown touchstart', function () {
                    if (!groupChanged) {
                        clearTimeout(timer);
                    }
                });

                if (groupHdr) {
                    $('.dwwl' + optionWheelIdx, dw).addClass('dw-select-gr');
                }

                if (multiple) {
                    dw.addClass('dwms');

                    $('.dwwl', dw).on('keydown', function (e) {
                        if (e.keyCode == 32) { // Space
                            e.preventDefault();
                            e.stopPropagation();
                            onTap($('.dw-sel', this));
                        }
                    }).eq(optionWheelIdx).attr('aria-multiselectable', 'true');

                    origValues = $.extend({}, selectedValues);
                }
            },
            validate: function (dw, i, time, dir) {
                var j,
                    v,
                    changes = [],
                    temp = inst.getArrayVal(true),
                    tempGr = temp[groupWheelIdx],
                    tempOpt = temp[optionWheelIdx],
                    t1 = $('.dw-ul', dw).eq(groupWheelIdx),
                    t2 = $('.dw-ul', dw).eq(optionWheelIdx);

                if (batchStart[groupWheelIdx] > 1) {
                    $('.dw-li', t1).slice(0, 2).removeClass('dw-v').addClass('dw-fv');
                }

                if (batchEnd[groupWheelIdx] < groupArray.length - 2) {
                    $('.dw-li', t1).slice(-2).removeClass('dw-v').addClass('dw-fv');
                }

                if (batchStart[optionWheelIdx] > 1) {
                    $('.dw-li', t2).slice(0, 2).removeClass('dw-v').addClass('dw-fv');
                }

                if (batchEnd[optionWheelIdx] < (groupSep ? groups[tempGr].options : optionArray).length - 2) {
                    $('.dw-li', t2).slice(-2).removeClass('dw-v').addClass('dw-fv');
                }

                if (!change) {
                    option = tempOpt;

                    if (groupWheel) {
                        group = options[option].group;

                        // If group changed, load group options
                        if (i === undefined || i === groupWheelIdx) {
                            group = +temp[groupWheelIdx];
                            groupChanged = false;
                            if (group !== prevGroup) {
                                option = groups[group].options[0].value;
                                batchStart[optionWheelIdx] = null;
                                batchEnd[optionWheelIdx] = null;
                                groupChanged = true;
                                s.readonly = [false, true];
                            } else {
                                s.readonly = origReadOnly;
                            }
                        }
                    }

                    // Adjust value to the first group option if group header was selected
                    if (hasGroups && (/__group/.test(option) || groupTap)) {
                        option = groups[options[groupTap || option].group].options[0].value;
                        tempOpt = option;
                        groupTap = false;
                    }

                    // Update values if changed
                    // Don't set the new option yet (if group changed), because it's not on the wheel yet
                    inst._tempWheelArray = groupWheel ? [tempGr, tempOpt] : [tempOpt];

                    // Generate new wheel batches
                    if (groupWheel) {
                        genGroupWheel(s.wheels);

                        if (batchChanged[groupWheelIdx]) {
                            changes.push(groupWheelIdx);
                        }
                    }

                    genOptWheel(s.wheels);

                    if (batchChanged[optionWheelIdx]) {
                        changes.push(optionWheelIdx);
                    }

                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        if (changes.length) {
                            change = true;
                            groupChanged = false;
                            prevGroup = group;

                            // Save current batch boundaries
                            batchStart[groupWheelIdx] = tempBatchStart[groupWheelIdx];
                            batchEnd[groupWheelIdx] = tempBatchEnd[groupWheelIdx];
                            batchStart[optionWheelIdx] = tempBatchStart[optionWheelIdx];
                            batchEnd[optionWheelIdx] = tempBatchEnd[optionWheelIdx];

                            // Set the updated values
                            inst._tempWheelArray = groupWheel ? [tempGr, option] : [option];

                            // Change the wheels
                            inst.changeWheel(changes, 0, i !== undefined);
                        }

                        if (groupWheel) {
                            if (i === optionWheelIdx) {
                                inst.scroll(t1, groupWheelIdx, inst.getValidCell(group, t1, dir, false, true).v, 0.1);
                            }
                            inst._tempWheelArray[groupWheelIdx] = group;
                        }

                        // Restore readonly status
                        s.readonly = origReadOnly;
                    }, i === undefined ? 100 : time * 1000);

                    if (changes.length) {
                        return groupChanged ? false : true;
                    }
                }

                // Add selected styling to selected elements in case of multiselect
                if (i === undefined && multiple) {
                    v = selectedValues;
                    j = 0;

                    $('.dwwl' + optionWheelIdx + ' .dw-li', dw).removeClass(selectedClass).removeAttr('aria-selected');

                    for (j in v) {
                        $('.dwwl' + optionWheelIdx + ' .dw-li[data-val="' + v[j] + '"]', dw).addClass(selectedClass).attr('aria-selected', 'true');
                    }
                }

                // Add styling to group headers
                if (groupHdr) {
                    $('.dw-li[data-val^="__group"]', dw).addClass('dw-w-gr');
                }

                // Disable invalid options
                $.each(s.invalid, function (i, v) {
                    $('.dw-li[data-val="' + v + '"]', t2).removeClass('dw-v dw-fv');
                });

                change = false;
            },
            onValidated: function () {
                option = inst._tempWheelArray[optionWheelIdx];
            },
            onClear: function (dw) {
                selectedValues = {};
                input.val('');
                $('.dwwl' + optionWheelIdx + ' .dw-li', dw).removeClass(selectedClass).removeAttr('aria-selected');
            },
            onCancel: function () {
                if (!inst.live && multiple) {
                    selectedValues = $.extend({}, origValues);
                }
            },
            onDestroy: function () {
                if (!input.hasClass('mbsc-control')) {
                    input.remove();
                }
                elm.removeClass('dw-hsel').removeAttr('tabindex');
            }
        };
    };
})(jQuery);

;
(function ($) {
    $.mobiscroll.i18n.zh = {
        // Core
        setText: '确定',
        cancelText: '取消',
        clearText: '明确',
        selectedText: '{count} 选',
        // Datetime component
        dateFormat: 'yy/mm/dd',
        dateOrder: 'yymmdd',
        dayNames: ['周日', '周一', '周二', '周三', '周四', '周五', '周六'],
        dayNamesShort: ['日', '一', '二', '三', '四', '五', '六'],
        dayNamesMin: ['日', '一', '二', '三', '四', '五', '六'],
        dayText: '日',
        hourText: '时',
        minuteText: '分',
        monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        monthNamesShort: ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二'],
        monthText: '月',
        secText: '秒',
        timeFormat: 'HH:ii',
        timeWheels: 'HHii',
        yearText: '年',
        nowText: '当前',
        pmText: '下午',
        amText: '上午',
        // Calendar component
        dateText: '日',
        timeText: '时间',
        calendarText: '日历',
        closeText: '关闭',
        // Daterange component
        fromText: '开始时间',
        toText: '结束时间',
        // Measurement components
        wholeText: '合计',
        fractionText: '分数',
        unitText: '单位',
        // Time / Timespan component
        labels: ['年', '月', '日', '小时', '分钟', '秒', ''],
        labelsShort: ['年', '月', '日', '点', '分', '秒', ''],
        // Timer component
        startText: '开始',
        stopText: '停止',
        resetText: '重置',
        lapText: '圈',
        hideText: '隐藏',
        // Listview
        backText: '背部',
        undoText: '复原',
        // Form
        offText: '关闭',
        onText: '开启',
        // Numpad
        decimalSeparator: ',',
        thousandsSeparator: ' '
    };
})(jQuery);

;
(function() {
	'use strict';
	angular.module('workorder.configurations', ['app.configurations.config']).config(function(globalConfig) {
		globalConfig.moduleCode = 'ticket';
	})
}).call(this);
;
// 
// Here is how to define your module 
// has dependent on mobile-angular-ui
// 
var app = angular.module('workorder', [
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

    'workorder.configurations',
    'workorder.controllers',
    'workorder.resource'

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
        .when('/flow/list',{
            templateUrl: 'views/flow-list.html',
            controller:'flowListController'
        })
        // .when('/apply/create',{
        //     templateUrl: 'views/record-form.html',
        //     controller:'recordCreateController'
        // })
        .when('/apply/list',{
            templateUrl: 'views/apply-list.html',
            controller:'applyListController',
            expand:{}
        })
        .when('/approval/list',{
            templateUrl: 'views/approval-list.html',
            controller:'approvalListController',
            expand:{}
        })
        // .when('/record/create/:flow_id/:department_id',{
        //     templateUrl: 'views/record-form.html',
        //     controller:'recordCreateController'
        // })
        .when('/record/create/:flow_id',{
            templateUrl: 'views/record-form.html',
            controller:'recordCreateController'
        })
        .when('/record/update/:id',{
            templateUrl: 'views/record-form.html',
            controller:'recordUpdateController'
        })
        .when('/record/detail/:id',{
            templateUrl: 'views/record-detail.html',
            controller:'recordDetailController'
        })
        .when('/auth',{
            templateUrl: '../../views/auth.html',
            controller:'authController'
        })
        .when('/qy_auth',{
            templateUrl: '../../views/auth.html',
            controller:'qyauthController'
        });
}]);
app.config( [ '$compileProvider',function( $compileProvider ){
        $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|tel|file|sms):/);
        $compileProvider.imgSrcSanitizationWhitelist(/^\s*(http|wxlocalresource|weixin):/);
    }
]);

app.config(['$httpProvider', function($httpProvider) {
    $httpProvider.interceptors.push('errorInterceptor');
    $httpProvider.defaults.withCredentials = true;
}]);

app.config(function(weChatConfig) {
    weChatConfig.jsApiList = ['chooseImage','previewImage','uploadImage','downloadImage']
});
;
(function() {
    'use strict';
    angular.module('workorder.controllers', [])
        .controller('mainController', ['$scope', '$location', '$route', 'userManager', 'user', function($scope, $location, $route, userManager, user) {
            $scope.showHeader = false;
            $scope.user = user;
            $scope.currentPath = '';
            $scope.$on('$routeChangeSuccess', function(e) {
                $scope.currentPath = $location.path();
            })
            $scope.isCurrentPath = function(paths) {
                return _.contains(paths.split(','), $scope.currentPath);
            };
        }])
        .controller('flowListController', ['$scope', '$timeout', '$location', 'globalPagination', 'globalConfig', 'modalExtension', 'flowApi',
            function($scope, $timeout, $location, globalPagination, globalConfig, modalExtension, flowApi) {
                $timeout(function() {
                    $scope.$parent.showHeader = false;
                }, 0)

                flowApi.get().$promise.then(function(res) {
                    // if (globalConfig.moduleCode != 'workorder' && data.length == 1) {
                    //     $location.path('/record/create/' + data[0].id + (data[0].department_id ? '/' + data[0].department_id : ''));
                    // }
                    if (res.errcode)
                        modalExtension.alert("目前没有你适用的审批流程");
                    else {
                        $scope.flows = res.data;
                        // angular.forEach($scope.flows, function(flow) {
                        //     if (flow.department_id) {
                        //         flow.show_department = _.where(data, { form_id: flow.form_id }).length > 1;
                        //     } else {
                        //         flow.show_department = false;
                        //     }
                        // })
                    }
                });
            }
        ])
        .controller('applyListController', ['$scope', '$timeout', '$location', '$route', 'globalPagination', 'recordApi',
            function($scope, $timeout, $location, $route, globalPagination, recordApi) {
                $timeout(function() {
                    $scope.$parent.showHeader = true;
                }, 0)
                $scope.condition = {
                    keyword: "",
                    status: "",
                    count: 15,
                    page: 0
                };
                $scope.page = {
                    totalCount: 0,
                    totalPage: 0
                };
                //初始化列表數據
                $scope.select = function() {

                    recordApi.recordList({ status: $scope.condition.status, page: $scope.condition.page, count: $scope.condition.count }).$promise.then(function(res) {
                        $scope.records = _.union($scope.records, res.data);
                        $scope.page.totalCount = res.page.total_count;
                        $scope.page.totalPage = res.page.total_page;
                        // console.log(res);
                    })
                };
                $scope.search = function() {
                    // $scope.condition_copy = angular.copy($scope.condition);
                    $scope.records = [];
                    $scope.select();
                }

                $scope.setStatus = function(status) {
                    $scope.condition.status = status;
                    $scope.condition.page = 0;
                    $scope.search();
                }

                $scope.bottomReached = function() {
                    if ($scope.condition.page < ($scope.page.totalPage - 1)) {
                        $scope.condition.page++;
                        $scope.select();
                    }
                }
                $scope.search();
            }
        ])
        .controller('approvalListController', ['$scope', '$timeout', '$location', '$route', 'globalPagination', 'recordApi', function($scope, $timeout, $location, $route, globalPagination, recordApi) {
            $timeout(function() {
                $scope.$parent.showHeader = true;
            }, 0)
            $scope.condition = {
                keyword: "",
                approval_status: "0",
                count: 15,
                page: 0
            };
            $scope.page = {
                totalCount: 0,
                totalPage: 0
            };
            //初始化列表數據
            $scope.pagination = globalPagination.create();
            $scope.pagination.resource = recordApi;
            $scope.pagination.query_method = 'approvalList';
            $scope.select = function() {
                recordApi.manageList({ status: $scope.condition.approval_status, page: $scope.condition.page, count: $scope.condition.count }).$promise.then(function(res) {
                    if (!res.errcode) {
                        $scope.records = _.union($scope.records, res.data);
                        $scope.page.totalCount = res.page.total_count;
                        $scope.page.totalPage = res.page.total_page;
                    }
                    // console.log(res);
                })
            };
            $scope.search = function() {
                // $scope.condition_copy = angular.copy($scope.condition);
                $scope.records = [];
                $scope.select();
            }

            $scope.setStatus = function(status) {
                $scope.condition.approval_status = status;
                $scope.condition.page = 0;
                $scope.search();
            }

            $scope.bottomReached = function() {
                if ($scope.condition.page < ($scope.page.totalPage - 1)) {
                    $scope.condition.page++;
                    $scope.select();
                }
            }

            $scope.search();
        }])
        .controller('recordCreateController', ['$scope', '$timeout', '$routeParams', '$location', 'globalFunction', 'modalExtension', 'weChat', 'flowApi', 'recordApi',
            function($scope, $timeout, $routeParams, $location, globalFunction, modalExtension, weChat, flowApi, recordApi) {
                $scope.is_submitting = false;
                $timeout(function() {
                    $scope.$parent.showHeader = false;
                }, 0)

                $scope.createOrder = {};
                $scope.isfirstCreate = true;
                $scope.createOrder.flow_id = Number($routeParams.flow_id);
                flowApi.get().$promise.then(function(res) {
                    $scope.flow = _.find(res.data, function(flow_child) {
                        return flow_child.id == $routeParams.flow_id
                    });

                    $scope.createOrder.form_id = $scope.flow.form_id;
                    recordApi.formField({ form_id: $scope.flow.form_id }).$promise.then(function(res) {
                        $scope.form = res.data;
                        // if ($routeParams.department_id)
                        //     $scope.flow.department_id = $routeParams.department_id
                        angular.forEach($scope.form.form_field, function(field) {
                                switch (field.type) {
                                    case 'form':
                                        field.value = $scope.flow.form_id;
                                        $scope.form_name = $scope.flow.name;
                                        break;
                                    case 'form_child':
                                        field.data = $scope.flow.form_child;
                                        break;
                                    case 'depart':
                                        field.value = res.data.department;
                                        break;
                                }
                            })
                            // $scope.special_fields = data.approvalSpecialFields;
                            // $scope.special_data = data.approvalSpecialData;
                            // $scope.fields = $scope.flow.form.approvalFormFields;
                        $scope.createOrder.form_field = $scope.form.form_field;
                    });
                });
                $scope.submit = function() {
                    $scope.is_submitting = true;
                    recordApi.save($scope.createOrder).$promise.then(function() {
                        modalExtension.alert($scope.flow.name + '提交成功').then(function() {
                            $location.path('/apply/list')
                        })
                    }, function() {
                        $scope.is_submitting = false;
                    })
                };
                $scope.selectImage = function(field) {
                    var upload = function(localIds, index) {
                        if (localIds.length <= index) return;
                        weChat.uploadImage({
                            localId: localIds[index],
                            isShowProgressTips: 1
                        }).then(function(uploadData) {
                            if (!_.isArray(field.value)) {
                                field.value = [];
                            }
                            field.value.push({ "path": localIds[index], "media_id": uploadData.serverId });
                            upload(localIds, index + 1);
                        })
                    }
                    weChat.chooseImage({
                        count: 9,
                        sizeType: ['original', 'compressed'],
                        sourceType: ['album', 'camera']
                    }).then(function(chooseData) {
                        upload(chooseData.localIds, 0);
                    }, function() {
                        alert('打开图片窗口失败')
                    })

                }
                $scope.showImage = function(field, image) {
                    weChat.previewImage({
                        current: image.path,
                        urls: _.pluck(field.value, 'path')
                    })
                }
                $scope.removeImage = function(field, index) {
                    field.value.splice(index, 1);
                }


            }
        ])
        .controller('recordUpdateController', ['$scope', '$timeout', '$routeParams', '$location', 'globalFunction', 'globalConfig', 'modalExtension', 'weChat', 'flowApi', 'recordApi',
            function($scope, $timeout, $routeParams, $location, globalFunction, globalConfig, modalExtension, weChat, flowApi, recordApi) {
                $scope.is_submitting = false;
                $scope.upload_url = globalConfig.uploadUrl;
                $timeout(function() {
                    $scope.$parent.showHeader = false;
                }, 0)
                $scope.isfirstCreate = false;
                $scope.form = {};
                $scope.form.record_id = Number($routeParams.id);
                recordApi.recordDetail({ id: $routeParams.id }).$promise.then(function(res) {
                    $scope.record = res.data;
                    $scope.form.form_field = res.data.ticketRecordFields;
                    $scope.form.update_time = res.data.update_time;
                    $scope.form.flow_id = res.data.flow_id;
                    // angular.forEach($scope.form.form_field, function(field) {
                    //         switch (field.type) {
                    //             case 'form':
                    //                 break;
                    //             case 'form_child':
                    //                 field.data = field.data.split(',');
                    //                 break;
                    //         }
                    //     })
                    // $scope.special_fields = data.approvalSpecialFields;
                    // $scope.special_data = data.approvalSpecialData;
                    // $scope.fields = $scope.record.approvalRecordFields;
                });
                $scope.submit = function() {
                    $scope.is_submitting = true;
                    recordApi.update($scope.form).$promise.then(function() {
                        modalExtension.alert($scope.record.form_name + '提交成功').then(function() {
                            $location.path('/apply/list')
                        })
                    }, function() {
                        $scope.is_submitting = false;
                    })
                }
                $scope.selectImage = function(field) {
                    weChat.chooseImage({
                        count: 1,
                        sizeType: ['original', 'compressed'],
                        sourceType: ['album', 'camera']
                    }).then(function(data) {
                        weChat.uploadImage({
                            localId: data.localIds[0],
                            isShowProgressTips: 1
                        }).then(function(data) {
                            if (!_.isArray(field.value)) {
                                field.value = [];
                            }
                            field.value.push({ "path": data.localId, "media_id": data.serverId });
                        })
                    }, function() {
                        alert('打开图片窗口失败')
                    })
                }
                $scope.showImage = function(field, image) {
                    weChat.previewImage({
                        current: image.id ? $scope.upload_url + '/' + image.path : image.path,
                        urls: _.map(field.value, function(image) {
                            return image.id ? $scope.upload_url + '/' + image.path : image.path
                        })
                    })
                }
                $scope.removeImage = function(field, index) {
                    field.value.splice(index, 1);
                }

            }
        ])
        .controller('recordDetailController', ['$scope', '$timeout', '$route', '$routeParams', '$location', 'globalFunction', 'globalConfig', 'weChat', 'ModalService', 'modalExtension', 'recordApi',
            function($scope, $timeout, $route, $routeParams, $location, globalFunction, globalConfig, weChat, ModalService, modalExtension, recordApi) {
                $scope.approval_info = {
                    comment: '',
                    result: '',
                    record_id: '',
                    update_time: ''
                };
                $scope.upload_url = globalConfig.uploadUrl;
                $scope.is_submitting = false;
                $timeout(function() {
                    $scope.$parent.showHeader = false;
                }, 0)
                recordApi.recordDetail({ id: $routeParams.id }).$promise.then(function(res) {
                    var approval_step;
                    $scope.record = res.data;
                    $scope.approval_info.record_id = $scope.record.id;
                    $scope.approval_info.update_time = $scope.record.update_time;
                    approval_step = _.groupBy($scope.record.ticketRecordSteps, 'index');
                    $scope.approval_step = [];
                    _.each(approval_step, function(step_data) {
                        var step;
                        if (step_data.length == 1) { //如果审批人只有一个，则直接返回
                            step = step_data[0];
                        } else {
                            if (step_data[0].type == 1) { //或签
                                step = _.findWhere(step_data, { "status": 1 }); //已审批
                                if (!step) {
                                    step = {
                                        "approver_user_name": _.pluck(step_data, 'approver_user_name').join('/'), //"/"表示或
                                        "status": 0 //未审批
                                    }
                                }
                            } else { //会签
                                var step_reject;
                                step = {
                                    "approver_user_name": _.pluck(step_data, 'approver_user_name').join(',') // ","表示且
                                }
                                step_reject = _.findWhere(step_data, { "approve_result": 0 });
                                if (step_reject) //会签中，只要有一人拒绝，那这一步就当是拒绝的
                                    step = step_reject;
                                else { //如果全部人都已审批，且没有人拒绝，则视为该步骤通过
                                    step.status = _.where(step_data, { "status": 0 }).length > 0 ? 0 : 1; //未审批
                                    if (step.status)
                                        step.approve_result = 1;
                                }

                            }
                        }
                        $scope.approval_step.push(step)
                    })
                });
                $scope.approval = function(result) {
                    $scope.approval_info.result = result;
                    if ($scope.approval_info.comment == '' && !result) {
                        modalExtension.error('驳回时必须填写审批意见');
                        return;
                    }
                    $scope.is_submitting = true;
                    recordApi.approve($scope.approval_info).$promise.then(function() {
                        modalExtension.alert('审批成功').then(function() {
                            $location.path('/approval/list')
                        });
                    }, function() {
                        $scope.is_submitting = false;
                    })
                }
                $scope.cancel = function() {
                    $scope.is_submitting = true;
                    recordApi.cancel({ record_id: $scope.record.id, update_time: $scope.record.update_time }).$promise.then(function() {
                        modalExtension.alert('撤销成功').then(function() {
                            $route.reload();
                        });
                    }, function() {
                        $scope.is_submitting = false;
                    })
                }
                $scope.resubmit = function() {
                    var loading;
                    $scope.is_submitting = true;
                    recordApi.recordDetail({ id: $scope.record.id }).$promise.then(function(res) {
                        if (res.data.update_time != $scope.record.update_time) {
                            modalExtension.error('申请已变更，请刷新页面');
                        } else {
                            $location.path('/record/update/' + $scope.record.id);
                        }
                    }, function() {
                        $scope.is_submitting = false;
                    })
                }
                $scope.showApprovalStep = function() {
                    ModalService.showModal({
                        templateUrl: "../workorder/views/approval-flow.html",
                        controller: ['$scope', 'close', 'record', function($scope, close, record) {
                            var approval_step;
                            $scope.record = record;
                            approval_step = _.groupBy(record.ticketRecordSteps, 'index');
                            $scope.approval_step = [];
                            _.each(approval_step, function(step_data) {
                                var step;
                                if (step_data.length == 1) {
                                    step = step_data[0];
                                } else {
                                    if (step_data[0].type == 1) { //或签
                                        step = _.findWhere(step_data, { "status": 1 }); //已审批
                                        if (!step) {
                                            step = {
                                                "approver_user_name": _.pluck(step_data, 'approver_user_name').join('/'),
                                                "status": 0 //未审批
                                            }
                                        }
                                    } else {
                                        $scope.approval_step = $scope.approval_step.concat(_.where(step_data, { status: 1 }));
                                        if (_.where(step_data, { status: 0 }).length > 0) {
                                            step = {
                                                "approver_user_name": _.pluck(_.where(step_data, { status: 0 }), 'approver_user_name').join(','),
                                                "status": 0 //未审批
                                            }
                                        }
                                    }
                                }
                                if (step)
                                    $scope.approval_step.push(step)

                            })
                            $scope.close = function() {
                                close(null, 0);
                            }
                            $scope.$on('$routeChangeStart', function(e) {
                                $scope.close();
                            })
                        }],
                        inputs: {
                            "record": $scope.record
                        }
                    });
                }
                $scope.showImage = function(field, url) {
                    weChat.previewImage({
                        current: url,
                        urls: _.map(field.value, function(image) {
                            return $scope.upload_url + '/' + image.path
                        })
                    })
                }
            }
        ])
        .filter('cut', function() {
            return function(value, wordwise, max, tail) {
                if (!value) return '';
                max = parseInt(max, 10);
                if (!max) return value;
                if (value.length <= max) return value;

                value = value.substr(0, max);

                if (wordwise) {
                    var lastspace = value.lastIndexOf(' ');
                    if (lastspace != -1) {
                        value = value.substr(0, lastspace);
                    }
                }

                return value + (tail || ' …');
            };
        });
}).call(this);

;
/**
 * Created by harry on 2015/3/26.
 */
(function() {
    'use strict';
    angular.module('workorder.resource',['ngResource'])
        .factory('flowApi',['globalFunction',function(globalFunction){
            return globalFunction.createResource('ticket/record/flow-list');
        }])
        .factory('recordApi',['globalFunction',function(globalFunction){
            return globalFunction.createResource('ticket',{},{
                'formField':{method:'post',url:('ticket/record/form-field')},
                'recordList':{method:'POST',url:('ticket/record/record-list')},
                'manageList':{method:'POST',url:('ticket/work-order/list')},
                'approve':{method:'POST',url:('ticket/work-order/approval')},
                'recordDetail':{method:'POST',url:('ticket/record/record-detail')},
                'save':{method:'POST',url:('ticket/record/record-create')},
                'update':{method:'POST',url:('ticket/record/record-update')},
                'cancel':{method:'POST',url:('ticket/record/record-cancel')},
            });
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
        .directive('pageLoading', [
            function() {
                return {
                    restrict: "A",
                    scope: { loading: "=", openLoading: "=" },
                    replace: true,
                    template: '<div ng-show="loading || openLoading" class="app-content-loading"></div>',
                    link: function(scope, ele, attrs) {
                        scope.$on('$routeChangeStart', function() {
                            scope.loading = true;
                        });
                        scope.$on('$routeChangeSuccess', function() {
                            scope.loading = false;
                        });
                    }
                }

            }
        ])
        .directive('msDate', [
            function() {
                return {
                    require: "ngModel",
                    restrict: "A",
                    link: function(scope, ele, attrs, modelCtrl) {
                        $(ele).mobiscroll().date({
                            dateFormat: 'yy-mm-dd',
                            display: 'bottom',
                            lang: 'zh',
                            onSelect: function(value, inst) {
                                modelCtrl.$setViewValue(value);
                                scope.$apply();
                            }
                        });
                        scope.$on('$routeChangeStart', function(e) {
                            $(ele).mobiscroll('destroy');
                        })
                    }
                }
            }
        ])
        .directive('msDateTime', [
            function() {
                return {
                    require: "ngModel",
                    restrict: "A",
                    link: function(scope, ele, attrs, modelCtrl) {
                        $(ele).mobiscroll().datetime({
                            dateFormat: 'yy-mm-dd',
                            display: 'bottom',
                            lang: 'zh',
                            onSelect: function(value, inst) {
                                modelCtrl.$setViewValue(value);
                                scope.$apply();
                            }
                        });
                        scope.$on('$routeChangeStart', function(e) {
                            $(ele).mobiscroll('destroy');
                        })
                    }
                }
            }
        ])
        .directive('msTime', [
            function() {
                return {
                    require: "ngModel",
                    restrict: "A",
                    link: function(scope, ele, attrs, modelCtrl) {
                        $(ele).mobiscroll().time({
                            display: 'bottom',
                            lang: 'zh',
                            onSelect: function(value, inst) {
                                modelCtrl.$setViewValue(value);
                                scope.$apply();
                            }
                        });
                        scope.$on('$routeChangeStart', function(e) {
                            $(ele).mobiscroll('destroy');
                        })
                    }
                }
            }
        ])
        .directive('msSelect', [
            function() {
                return {
                    require: "ngModel",
                    restrict: "A",
                    link: function(scope, ele, attrs, modelCtrl) {
                        setTimeout(function() {
                            $(ele).mobiscroll().select({
                                display: 'bottom',
                                lang: 'zh',
                                multiple: attrs.multiple,
                                placeholder: attrs.placeholder,
                                onSelect: function(value, inst) {
                                    if (attrs.item) {
                                        var selectItem = _.find(angular.fromJson(attrs.item), function(child) {
                                            return child.name == value
                                        });
                                        modelCtrl.$setViewValue(selectItem.id);
                                        scope.$apply();
                                        scope.createOrder.form_child_id = selectItem.id;
                                    } else {
                                        modelCtrl.$setViewValue(inst._tempValue);
                                        scope.$apply();
                                    }
                                }
                            });
                        }, 10)
                        scope.$on('$routeChangeStart', function(e) {
                            $(ele).mobiscroll('destroy');
                        })
                    }
                }
            }
        ])
}).call(this);

;
(function() {
    'use strict';
    angular.module('app.controllers.auth', [])
        .controller('authController', ['$rootScope', '$scope', '$location', '$route', 'userManager', 'user', 'globalConfig', function($rootScope, $scope, $location, $route, userManager, user, globalConfig) {
            $rootScope.openLoading = true;
            var params = $location.search();
            // console.log(params);
            userManager.restorageUserInfo();
            if (user.checkAuth()) {
                $rootScope.openLoading = false;
                $location.path(decodeURIComponent(params.url));
            } else {
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
                result.then(function(data) {
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
