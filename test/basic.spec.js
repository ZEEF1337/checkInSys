const chai = require("chai");
const chaiHttp = require("chai-http");
var assert = require('assert');

const { expect, should } = chai;
chai.use(chaiHttp);

describe('Basic Mocha String Test', function () {
    it('should return number of charachters in a string', function () {
           assert.equal("Hello".length, 4);
       }); it('should return first charachter of the string', function () {
           assert.equal("Hello".charAt(0), 'H');
       });
   });