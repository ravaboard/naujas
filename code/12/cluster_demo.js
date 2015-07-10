var cluster = require('cluster');
var http = require('http');
var numCPUs = require('os').cpus().length;
console.log(require('os').hostname());
console.log(require('os').freemem());
console.log(require('os').type());
console.log(require('os').release());
console.log(require('os').platform());
console.log(require('os').uptime());
console.log(require('os').networkInterfaces());
if (cluster.isMaster) {
  for (var i = 0; i < numCPUs; i++) {
    cluster.fork();
  }

  cluster.on('exit', function(worker, code, signal) {
    console.log('Worker ' + worker.process.pid + ' died.');
  });
} else {
  http.Server(function(req, res) {
    res.writeHead(200);
    res.end('I am a worker running in process ' + process.pid);
  }).listen(8000);
}
