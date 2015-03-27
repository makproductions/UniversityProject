@extends('app')

@section('content')

<script>
    var ready_queue = new Array();
    var table_process = new Array();
    var schedule;
    function compare(p1, p2) {
        return (p1.time - p1.executed) - (p2.time - p2.executed);
    }
    var rr = 0;//constant for round robin
    var srt = 1;//constant for shortest time first
    var fcfs = 2;//constant for first come first served
    var ticks = 0;//time elapsed in ticks
    function CPU()//consumer
    {
        if (ready_queue.length > 0)//verify is exist process in queue
        {
            var aux, i;
            switch (schedule) {
                case rr:
                    ready_queue[0].executed++;//execute process
                    if (ready_queue[0].executed % ready_queue[0].quant == 0)//verify if quantum time is finished
                    {
                        aux = ready_queue[0];//remember first process in queue
                        for (i = 0; i < (ready_queue.length - 1); i++) {
                            ready_queue[i] = ready_queue[i + 1];//move process to the left
                        }
                        ready_queue[ready_queue.length - 1] = aux;//put the first proces last
                    }
                    for (i = 0; i < ready_queue.length; i++) {
                        if (ready_queue[i].executed == ready_queue[i].time) {
                            put_in_table(ready_queue[i]);
                            ready_queue.splice(i, 1);//remove the process when quantum time is finished
                        }

                        if (i > 0) {
						if(ready_queue[i].wait){
                            ready_queue[i].wait++;//register wait time in queue
							}
                            wait++;//the total wait time register for each process and is a sum of wait time
                        }
                    }
                    break;

                case srt:
                    ready_queue.sort(compare);//sort using compare function to compare every time the process is executed
                    ready_queue[0].executed++;//execute process
                    for (i = 0; i < ready_queue.length; i++) {
                        if (ready_queue[i].executed == ready_queue[i].time) {
                            put_in_table(ready_queue[i]);
                            ready_queue.splice(i, 1);//remove process that is executed
                        }
                        if (i > 0) {
                            ready_queue[i].wait++;
                            wait++;
                        }
                    }
                    break;

                case fcfs:
                    ready_queue[0].executed++;
                    for (i = 0; i < ready_queue.length; i++) {
                        if (ready_queue[i].executed == ready_queue[i].time) {
                            put_in_table(ready_queue[i]);
                            ready_queue.splice(i, 1);
                        }
                        if (i > 0) {
                            ready_queue[i].wait++;
                            wait++;
                        }
                    }
                    break;
            }
        }
        ticks++;
    }
    function shower()//this function show what happen
    {

        var sim = document.getElementById('sim');
        sim.innerHTML = "";
        var div;
        var div_list;
        var i;
        if (ready_queue.length > 0) {
            for (i = 0; i < ready_queue.length; i++) {

                div = document.createElement("div");

                if (i == 0) {
                    div.setAttribute("class", "CPU");
                    div.innerHTML = "<span>CPU<br/>Process:" + ready_queue[i].number + "<br> Ticks:" + ready_queue[i].executed + "/" + ready_queue[i].time + "</span>";
                    ready_queue[i].highlight = true;

                }
                else {
                    div_list = document.createElement("div");
                    div_list.setAttribute("class", "arrow");
                    sim.appendChild(div_list);
                    div.setAttribute("class", "process_ready");
                    div.innerHTML = "P" + ready_queue[i].number + " " + ready_queue[i].executed + "/" + ready_queue[i].time;
                    ready_queue[i].highlight = false;

                }
                sim.appendChild(div);

            }
        }
        else {
            div = document.createElement("div");
            div.setAttribute("class", "CPU");

            div.innerHTML = "<span>CPU</span>";
            sim.appendChild(div);
        }
        div = document.createElement("hr");
        sim.appendChild(div);
        div = document.createElement("div");
        div.setAttribute("class", "queueitems");
        sim.appendChild(div);
        for (i = 0; i < table_process.length; i++) {
            div_list = document.createElement("div");
            if (table_process[i].highlight == true)
                div_list.setAttribute("class", "highlight");
            else
                div_list.setAttribute("class", "process");
            div_list.innerHTML = "Process: " + table_process[i].number + "<br>Ticks: " + table_process[i].executed + "/" + table_process[i].time + "<br>Arival: " + table_process[i].arrive + "<br>Quantum: " + table_process[i].quant;
            div.appendChild(div_list);
        }
        div = document.createElement("div");
        div.innerHTML = "";
        document.getElementById("stats").innerHTML = "<br/><div style='width:100%; height:30px;'><span class='counterc' style='float:left;'>Average WaitTime: " + average_wait() + "</span><span class='counterc' style='text-align: right; float:right;'> Ticks: " + ticks + "</span></div>";
        sim.appendChild(div);
        if (table_process.length == 0)
            stop();

    }
    function refresh() {
        if (ready_queue.length > 0) {
            ready_queue.splice(0, ready_queue.length);//remove all element in queue
            ready_queue[0].highlight = false;
        }
        clearInterval(sim_cpu);
        clearInterval(sim_show);
        clearInterval(show_ar);
    }
    //create table head
    table_view = document.createElement("table");
    table_view.setAttribute("border", "1");
    var tr;
    tr = document.createElement("tr");
    table_view.appendChild(tr);
    var th;
    th = document.createElement("th");
    th.innerHTML = "Process number";
    tr.appendChild(th);
    th = document.createElement("th");
    th.innerHTML = "Arrival time";
    tr.appendChild(th);
    th = document.createElement("th");
    th.innerHTML = "Execute time";
    tr.appendChild(th);
    th = document.createElement("th");
    th.innerHTML = "Service time";
    tr.appendChild(th);
    function put_in_table(pro) {
        var row = document.createElement("tr");
        table_view.appendChild(row);
        var col;
        col = document.createElement("td");
        col.innerHTML = pro.number;
        row.appendChild(col);
        col = document.createElement("td");
        col.innerHTML = pro.arrive;
        row.appendChild(col);
        col = document.createElement("td");
        col.innerHTML = pro.time;
        row.appendChild(col);
        col = document.createElement("td");
        col.innerHTML = "" + (pro.wait + pro.arrive);
        row.appendChild(col);
    }
    function stop() {
        clearInterval(sim_cpu);
        clearInterval(sim_show);
        clearInterval(show_ar);
        paused = true;
        document.getElementById("pause").value = "continue";
        ticks = 0;
        document.getElementById('sim').innerHTML = "";
        var i;
        for (i = 0; i < table_process.length; i++) {
            table_process[i].executed = 0;
            table_process[i].ready = false;
            table_process[i].wait = 0;
        }
        ready_queue.splice(0, ready_queue.length);//clear ready queue;
        wait = 0;
        process_counter = table_process.length;
        //shower();
    }
    function arrival()//is a producer and consumer producer for CPU and consumer for user;
    {
        var i;
        for (i = 0; i < table_process.length; i++) {
            if (table_process[i].arrive <= ticks && table_process[i].ready == false) {
                table_process[i].ready = true;
                ready_queue.push(table_process[i]);
            }
            if (table_process[i].executed == table_process[i].time)
                table_process.splice(i, 1);
        }
    }
    var process_counter = 0;
    var wait = 0;
	function resetStyle () {
		document.getElementById("at").style.border = "";
		document.getElementById("qt").style.border = "";
		document.getElementById("pt").style.border = "";
	}
    function add_process()//the producer is user it self
    {
        var pn = parseInt(document.getElementById("pn").value);
        var qt = parseInt(document.getElementById("qt").value);
        var at = parseInt(document.getElementById("at").value);
        var pt = parseInt(document.getElementById("pt").value);
		
        var run = true;
        if ((isNaN(pt)) || (pt.toString().indexOf('-') != -1) || pt <= 0) {
			resetStyle();
            document.getElementById("pt").style.border = "1px solid red";
            run = false;
			toastr["warning"]("Can only contain Positive Numbers", "Process Time");
        }else if (isNaN(qt) || (qt.toString().indexOf('-') != -1) || qt <= 0) {
            resetStyle();
			document.getElementById("qt").style.border = "1px solid red";
			toastr["warning"]("Can only contain Positive Numbers", "Quantum Time");
            run = false;
        } else if (isNaN(at) || (at.toString().indexOf('-') != -1) || at <= 0) {
            resetStyle();
			document.getElementById("at").style.border = "1px solid red";
			toastr["warning"]("Can only contain Positive Numbers", "Arrival Time");
            run = false;
        } else {
            document.getElementById("at").style.border = "";
			document.getElementById("qt").style.border = "";
			document.getElementById("pt").style.border = "";
            run = true;
			console.log("gothere");
        }
		
        if (run == true) {
            document.getElementById("pn").value = pn + 1;
            var process = new Object();
            process.time = pt;//add time to execute
            process.wait = 0;//wait time is initial zero
            process.quant = qt;//quantum time for round robin
            process.executed = 0;//initial no time is executed
            process.arrive = at;//se the tick time when arrive to ready queue
            process.number = pn;//se process number
            process.ready = false;//initial is not ready or highlighted
            process.highlight = false;
            table_process.push(process);//push the adress of process in table process
            process_counter++;//count number of proccess
            shower();
        }
    }
    function remove_process()//user consume to eliminate process
    {
		var run;
		var i;
        var rp = parseInt(document.getElementById("pn1").value);
		if ((isNaN(rp)) || (rp.toString().indexOf('-') != -1) || rp <= 0) {
				resetStyle();
				document.getElementById("pn1").style.border = "1px solid red";
				run = false;
				toastr["warning"]("Can only contain Positive Numbers", "Remove Process");
			}
		if(run == true){
			for (i = 0; i < table_process.length; i++) {
				if (table_process[i].number == pn)
					table_process.splice(i, 1);//remove the address of process from table_process
			}
			shower();
		}
    }
    var sim_cpu;
    var sim_show;
    var show_ar;
    function pauser() {
	
	if (paused == true){
	document.getElementById("pause").innerHTML = "Pause";
	}else if(paused == false){
	document.getElementById("pause").innerHTML = "Continue";
	}
        if (paused == false) {
            clearInterval(sim_cpu);
            clearInterval(sim_show);//pause procesor and
            clearInterval(show_ar);
            paused = true;//se paus true
           
        }
        else {
            sim_cpu = setInterval(CPU, wait_time);
            sim_show = setInterval(shower, wait_time / 2);
            show_ar = setInterval(arrival, wait_time);
            paused = false;
        }
    }
    var paused = true;
    var wait_time;
    function fcfs_func() {
		disPause();
        refresh();
        ticks = 0;
        schedule = fcfs;
        wait_time = parseInt(document.getElementById("wt").value);
        sim_cpu = setInterval(CPU, wait_time);//start the simulation and store interval in sim_cpu
        sim_show = setInterval(shower, wait_time / 2);//start shower function
        show_ar = setInterval(arrival, wait_time);//start the arrival function from table to ready queue

        submit('FCFS');
    }
    function rr_func() {
		disPause();
        refresh();
        ticks = 0;
        schedule = rr;//set schedule for round robin
        wait_time = parseInt(document.getElementById("wt").value);

        sim_cpu = setInterval(CPU, wait_time);
        sim_show = setInterval(shower, wait_time / 2);
        show_ar = setInterval(arrival, wait_time);

        submit('RR');
    }
    function srt_func() {
		disPause();
        refresh()
        ticks = 0;
        schedule = srt;//schedul srt set
        wait_time = parseInt(document.getElementById("wt").value);

        sim_cpu = setInterval(CPU, wait_time);
        sim_show = setInterval(shower, wait_time / 2);
        show_ar = setInterval(arrival, wait_time);

        submit('SRT');
    }
	function disPause() {
	    document.getElementById("pause").disabled = false;
        paused = false;
        document.getElementById("pause").innerHTML = "Pause";
	}
    function average_wait() {
        var i;
        return wait / process_counter;

    }

    function outputUpdate(vol) {
        document.querySelector('#simSpeed').value = vol + " ms";
        wait_time = parseInt(document.getElementById("wt").value);
    }
</script>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">Add Process</div>
                    <div class="panel-body">
                        <form>
                            <fieldset>
                                <!--Process Time -->
                                <p class="l1"><label for="pt">Process Time</label></p>

                                <p class="l0"><input type="number" id="pt" min="1" required></p>

                                <!--Process Number -->
                                <p class="l1"><label for="pn">Process Number</label></p>

                                <p class="l0"><input type="number" id="pn" value="1" readonly></p>

                                <!--Quantum Time -->
                                <p class="l1"><label for="qt">Quantum Time (RR) </label></p>

                                <p class="l0"><input type="number" id="qt" min="1" required></p>

                                <!--Arrival Time -->
                                <p class="l1"><label for="at">Arrival Time</label></p>

                                <p class="l0"><input type="number" id="at" min="1" required></p>

                                <!--Process Number -->
                                <p class="l1"><label for="wt">Simulation Speed</label></p>
                                <!--<p><input type="number" id="wt"></p>-->
                                <p class="l0"><input type="range" min="100" max="2100" value="1000" id="wt" step="500"
                                          onchange="outputUpdate(value)">
                                    <output for="wt" id="simSpeed" style="margin-left: 10px;">1000 ms</output>
                                </p>

                                <button type="button" class="btn btn-default" value="Add Process"
                                        onclick="add_process()">
                                    Add Process
                                </button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Processor</div>
                    <div class="panel-body">
                        <form>
                            <fieldset>
                                <!--<input type="button" id="stop hoverw" onclick="stop()" value="stop" >-->
                                <button type="button" id="pause" class="btn btn-default" onclick="pauser()"
                                        value="Continue"
                                        disabled>
                                    Continue
                                </button>

                                <div id="stats"></div>
                                <div id="sim">
                                    <div id="queue">

                                    </div>

                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">Scheduling Method</div>
                    <div class="panel-body">
                        <form>
                            <div class="btn-group-vertical" role="group" aria-label="...">
                                <button type="button" class="btn btn-default" onclick="fcfs_func()"
                                        value="first-come-first-served">First Come First Served
                                </button>
                                <button type="button" class="btn btn-default" onclick="rr_func()"
                                        value="round robin">
                                    Round Robin
                                </button>
                                <button type="button" class="btn btn-default" onclick="srt_func()"
                                        value="shortest-remaining-time">
                                    Shortest Remaining Time
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Remove Process</div>
                    <div class="panel-body">
                        <form>
                            <fieldset>
                                <p><label for="pn1">Process Number</label></p>

                                <p><input type="number" id="pn1" value="1"></p>
                                <button type="button" class="btn btn-default" onclick="remove_process()"
                                        value="Remove Method">
                                    Remove Method
                                </button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Results Table</div>
                    <div class="panel-body">
                        <div id="tables_view"
                             style="display: block;  float: left;  clear: left;  margin-top: 10px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    document.getElementById("tables_view").appendChild(table_view);
    function submit(method) {
        $.ajax({
            type: 'GET'
            , url: '{{ url('/new') }}'
            , data: {
                'processes': table_process
                , 'method': method
                , 'session_id': session_id
            }
            , dataType: 'json'
            , success: function (data) {
                alert(data);
            }
        });
    }
</script>
    <script>
        var session_id = '{{$session_id}}';
        function updateSession() {
            $.ajax('{{url('/end')}}/' + session_id);
        }
        $(document).on('ready', function () {
            setInterval(updateSession, 5000);
        });
    </script>
@endsection
