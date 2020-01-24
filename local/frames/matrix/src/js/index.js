'use strict';

['ga', 'quiz'].map(function(name) {
    require('./' + name + '.js').init();
});