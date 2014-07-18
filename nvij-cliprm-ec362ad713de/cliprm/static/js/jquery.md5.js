/**
 * jQuery MD5 hash algorithm function
 * 
 * 	<code>
 * 		Calculate the md5 hash of a String 
 * 		String $.md5 ( String str )
 * 	</code>
 * 
 * Calculates the MD5 hash of str using the Â» RSA Data Security, Inc. MD5 Message-Digest Algorithm, and returns that hash. 
 * MD5 (Message-Digest algorithm 5) is a widely-used cryptographic hash function with a 128-bit hash value. MD5 has been employed in a wide variety of security applications, and is also commonly used to check the integrity of data. The generated hash is also non-reversable. Data cannot be retrieved from the message digest, the digest uniquely identifies the data.
 * MD5 was developed by Professor Ronald L. Rivest in 1994. Its 128 bit (16 byte) message digest makes it a faster implementation than SHA-1.
 * This script is used to process a variable length message into a fixed-length output of 128 bits using the MD5 algorithm. It is fully compatible with UTF-8 encoding. It is very useful when u want to transfer encrypted passwords over the internet. If you plan using UTF-8 encoding in your project don't forget to set the page encoding to UTF-8 (Content-Type meta tag). 
 * This function orginally get from the WebToolkit and rewrite for using as the jQuery plugin.
 * 
 * Example
 * 	Code
 * 		<code>
 * 			$.md5("I'm Persian."); 
 * 		</code>
 * 	Result
 * 		<code>
 * 			"b8c901d0f02223f9761016cfff9d68df"
 * 		</code>
 * 
 * @alias Muhammad Hussein Fattahizadeh < muhammad [AT] semnanweb [DOT] com >
 * @link http://www.semnanweb.com/jquery-plugin/md5.html
 * @see http://www.webtoolkit.info/
 * @license http://www.gnu.org/licenses/gpl.html [GNU General Public License]
 * @param {jQuery} {md5:function(string))
 * @return string
 */
(function(s){var l=function(a,c){var g,h,j,k,b;j=a&2147483648;k=c&2147483648;g=a&1073741824;h=c&1073741824;b=(a&1073741823)+(c&1073741823);return g&h?b^2147483648^j^k:g|h?b&1073741824?b^3221225472^j^k:b^1073741824^j^k:b^j^k},m=function(a,c,g,h,j,k,b){a=l(a,l(l(c&g|~c&h,j),b));return l(a<<k|a>>>32-k,c)},n=function(a,c,g,h,j,k,b){a=l(a,l(l(c&h|g&~h,j),b));return l(a<<k|a>>>32-k,c)},p=function(a,c,g,h,j,k,b){a=l(a,l(l(c^g^h,j),b));return l(a<<k|a>>>32-k,c)},q=function(a,c,g,h,j,k,b){a=l(a,l(l(g^(c|~h),
j),b));return l(a<<k|a>>>32-k,c)},r=function(a){var c="",g="",h;for(h=0;3>=h;h++)g=a>>>8*h&255,g="0"+g.toString(16),c+=g.substr(g.length-2,2);return c};s.extend({md5:function(a){var c=[],g,h,j,k,b,d,e,f,c=a.replace(/\x0d\x0a/g,"\n");a="";for(g=0;g<c.length;g++)h=c.charCodeAt(g),128>h?a+=String.fromCharCode(h):(127<h&&2048>h?a+=String.fromCharCode(h>>6|192):(a+=String.fromCharCode(h>>12|224),a+=String.fromCharCode(h>>6&63|128)),a+=String.fromCharCode(h&63|128));c=a;a=c.length;g=a+8;h=16*((g-g%64)/
64+1);j=Array(h-1);for(b=k=0;b<a;)g=(b-b%4)/4,k=8*(b%4),j[g]|=c.charCodeAt(b)<<k,b++;g=(b-b%4)/4;j[g]|=128<<8*(b%4);j[h-2]=a<<3;j[h-1]=a>>>29;c=j;b=1732584193;d=4023233417;e=2562383102;f=271733878;for(a=0;a<c.length;a+=16)g=b,h=d,j=e,k=f,b=m(b,d,e,f,c[a+0],7,3614090360),f=m(f,b,d,e,c[a+1],12,3905402710),e=m(e,f,b,d,c[a+2],17,606105819),d=m(d,e,f,b,c[a+3],22,3250441966),b=m(b,d,e,f,c[a+4],7,4118548399),f=m(f,b,d,e,c[a+5],12,1200080426),e=m(e,f,b,d,c[a+6],17,2821735955),d=m(d,e,f,b,c[a+7],22,4249261313),
b=m(b,d,e,f,c[a+8],7,1770035416),f=m(f,b,d,e,c[a+9],12,2336552879),e=m(e,f,b,d,c[a+10],17,4294925233),d=m(d,e,f,b,c[a+11],22,2304563134),b=m(b,d,e,f,c[a+12],7,1804603682),f=m(f,b,d,e,c[a+13],12,4254626195),e=m(e,f,b,d,c[a+14],17,2792965006),d=m(d,e,f,b,c[a+15],22,1236535329),b=n(b,d,e,f,c[a+1],5,4129170786),f=n(f,b,d,e,c[a+6],9,3225465664),e=n(e,f,b,d,c[a+11],14,643717713),d=n(d,e,f,b,c[a+0],20,3921069994),b=n(b,d,e,f,c[a+5],5,3593408605),f=n(f,b,d,e,c[a+10],9,38016083),e=n(e,f,b,d,c[a+15],14,3634488961),
d=n(d,e,f,b,c[a+4],20,3889429448),b=n(b,d,e,f,c[a+9],5,568446438),f=n(f,b,d,e,c[a+14],9,3275163606),e=n(e,f,b,d,c[a+3],14,4107603335),d=n(d,e,f,b,c[a+8],20,1163531501),b=n(b,d,e,f,c[a+13],5,2850285829),f=n(f,b,d,e,c[a+2],9,4243563512),e=n(e,f,b,d,c[a+7],14,1735328473),d=n(d,e,f,b,c[a+12],20,2368359562),b=p(b,d,e,f,c[a+5],4,4294588738),f=p(f,b,d,e,c[a+8],11,2272392833),e=p(e,f,b,d,c[a+11],16,1839030562),d=p(d,e,f,b,c[a+14],23,4259657740),b=p(b,d,e,f,c[a+1],4,2763975236),f=p(f,b,d,e,c[a+4],11,1272893353),
e=p(e,f,b,d,c[a+7],16,4139469664),d=p(d,e,f,b,c[a+10],23,3200236656),b=p(b,d,e,f,c[a+13],4,681279174),f=p(f,b,d,e,c[a+0],11,3936430074),e=p(e,f,b,d,c[a+3],16,3572445317),d=p(d,e,f,b,c[a+6],23,76029189),b=p(b,d,e,f,c[a+9],4,3654602809),f=p(f,b,d,e,c[a+12],11,3873151461),e=p(e,f,b,d,c[a+15],16,530742520),d=p(d,e,f,b,c[a+2],23,3299628645),b=q(b,d,e,f,c[a+0],6,4096336452),f=q(f,b,d,e,c[a+7],10,1126891415),e=q(e,f,b,d,c[a+14],15,2878612391),d=q(d,e,f,b,c[a+5],21,4237533241),b=q(b,d,e,f,c[a+12],6,1700485571),
f=q(f,b,d,e,c[a+3],10,2399980690),e=q(e,f,b,d,c[a+10],15,4293915773),d=q(d,e,f,b,c[a+1],21,2240044497),b=q(b,d,e,f,c[a+8],6,1873313359),f=q(f,b,d,e,c[a+15],10,4264355552),e=q(e,f,b,d,c[a+6],15,2734768916),d=q(d,e,f,b,c[a+13],21,1309151649),b=q(b,d,e,f,c[a+4],6,4149444226),f=q(f,b,d,e,c[a+11],10,3174756917),e=q(e,f,b,d,c[a+2],15,718787259),d=q(d,e,f,b,c[a+9],21,3951481745),b=l(b,g),d=l(d,h),e=l(e,j),f=l(f,k);return(r(b)+r(d)+r(e)+r(f)).toLowerCase()}})})(jQuery);