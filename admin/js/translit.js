var alphabet = new Array();

/*rus*/
alphabet['А'] = 'a';   alphabet['а'] = 'a';
alphabet['Б'] = 'b';   alphabet['б'] = 'b';
alphabet['В'] = 'v';   alphabet['в'] = 'v';
alphabet['Г'] = 'g';   alphabet['г'] = 'g';
alphabet['Д'] = 'd';   alphabet['д'] = 'd';
alphabet['Е'] = 'e';   alphabet['е'] = 'e';
alphabet['И'] = 'i';   alphabet['и'] = 'i';
alphabet['Ё'] = 'yo';  alphabet['ё'] = 'yo';
alphabet['Ж'] = 'zh';  alphabet['ж'] = 'zh';
alphabet['З'] = 'z';   alphabet['з'] = 'z';
alphabet['И'] = 'i';   alphabet['и'] = 'i';
alphabet['Й'] = 'j';   alphabet['й'] = 'j';
alphabet['К'] = 'k';   alphabet['к'] = 'k';
alphabet['Л'] = 'l';   alphabet['л'] = 'l';
alphabet['М'] = 'm';   alphabet['м'] = 'm';
alphabet['Н'] = 'n';   alphabet['н'] = 'n';
alphabet['О'] = 'o';   alphabet['о'] = 'o';
alphabet['П'] = 'p';   alphabet['п'] = 'p';
alphabet['Р'] = 'r';   alphabet['р'] = 'r';
alphabet['С'] = 's';   alphabet['с'] = 's';
alphabet['Т'] = 't';   alphabet['т'] = 't';
alphabet['У'] = 'u';   alphabet['у'] = 'u';
alphabet['Ф'] = 'f';   alphabet['ф'] = 'f';
alphabet['Х'] = 'kh';  alphabet['х'] = 'kh';
alphabet['Ц'] = 'c';   alphabet['ц'] = 'c';
alphabet['Ч'] = 'ch';  alphabet['ч'] = 'ch';
alphabet['Ш'] = 'sh';  alphabet['ш'] = 'sh';
alphabet['Щ'] = 'shh'; alphabet['щ'] = 'shh';
alphabet['Ъ'] = '';    alphabet['ъ'] = '';
alphabet['Ы'] = 'y';   alphabet['ы'] = 'y';
alphabet['Ь'] = '';    alphabet['ь'] = '';
alphabet['Э'] = 'je';  alphabet['э'] = 'ye';
alphabet['Ю'] = 'ju';  alphabet['ю'] = 'yu';
alphabet['Я'] = 'ja';  alphabet['я'] = 'ya';

/*ukr*/
alphabet['Ґ'] = 'g';  alphabet['ґ'] = 'g';
alphabet['Є'] = 'ye'; alphabet['є'] = 'ye';
alphabet['І'] = 'i';  alphabet['і'] = 'i';
alphabet['Ї'] = 'j';  alphabet['ї'] = 'j';

/*eng*/
alphabet['A'] = 'a';  alphabet['a'] = 'a';
alphabet['B'] = 'b';  alphabet['b'] = 'b';
alphabet['C'] = 'c';  alphabet['c'] = 'c';
alphabet['D'] = 'd';  alphabet['d'] = 'd';
alphabet['E'] = 'e';  alphabet['e'] = 'e';
alphabet['F'] = 'f';  alphabet['f'] = 'f';
alphabet['G'] = 'g';  alphabet['g'] = 'g';
alphabet['H'] = 'h';  alphabet['h'] = 'h';
alphabet['I'] = 'i';  alphabet['i'] = 'i';
alphabet['J'] = 'j';  alphabet['j'] = 'j';
alphabet['K'] = 'k';  alphabet['k'] = 'k';
alphabet['L'] = 'l';  alphabet['l'] = 'l';
alphabet['M'] = 'm';  alphabet['m'] = 'm';
alphabet['N'] = 'n';  alphabet['n'] = 'n';
alphabet['O'] = 'o';  alphabet['o'] = 'o';
alphabet['P'] = 'p';  alphabet['p'] = 'p';
alphabet['Q'] = 'q';  alphabet['q'] = 'q';
alphabet['R'] = 'r';  alphabet['r'] = 'r';
alphabet['S'] = 's';  alphabet['s'] = 's';
alphabet['T'] = 't';  alphabet['t'] = 't';
alphabet['U'] = 'u';  alphabet['u'] = 'u';
alphabet['V'] = 'v';  alphabet['v'] = 'v';
alphabet['W'] = 'w';  alphabet['w'] = 'w';
alphabet['X'] = 'x';  alphabet['x'] = 'x';
alphabet['Y'] = 'y';  alphabet['y'] = 'y';
alphabet['Z'] = 'z';  alphabet['z'] = 'z';

/*symbol*/
alphabet['<']  = '';  alphabet['>'] = '';
alphabet['\''] = '';  alphabet['"'] = '';
alphabet['.']  = '';  alphabet[','] = '';
alphabet[';']  = '';  alphabet['!'] = '';
alphabet['#']  = '';  alphabet['$'] = '';
alphabet['%']  = '';  alphabet['^'] = '';
alphabet['*']  = '';  alphabet['_'] = '-';
alphabet['']   = '';  alphabet['']  = '';
alphabet['=']  = '';  alphabet['+'] = '';
alphabet['?']  = '';  alphabet['`'] = '';
alphabet['@']  = '';  alphabet['№'] = '';
alphabet[' ']  = '-'; alphabet['-'] = '-';
alphabet['(']  = '';  alphabet[')'] = '';
alphabet['«']  = '';  alphabet['»'] = '';
alphabet['–']  = '';  alphabet[':']  = '';
alphabet['&']  = '';  alphabet['/']  = '-';
alphabet['\\']  = '-';

/*number*/
alphabet['0']  = '0'; alphabet['1'] = '1';
alphabet['2']  = '2'; alphabet['3'] = '3';
alphabet['4']  = '4'; alphabet['5'] = '5';
alphabet['6']  = '6'; alphabet['7'] = '7';
alphabet['8']  = '8'; alphabet['9'] = '9';


function my_replace(array, string){
	var my_string = '';
	for (var i = 0; i<string.length ; i++) {
        if (alphabet[string.charAt(i)] !== undefined) {
            my_string += alphabet[string.charAt(i)];
        }
    }
	return my_string.replace(/(^[-]*)?([-]*$)?/gi, "").replace(/[-]+/gi, "-");
}