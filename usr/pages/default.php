<?
require_once __DIR__ . "/../../internal/Capabilities.php";
Capabilities::load(array(Capabilities::$BASE_MODULE, Capabilities::$COLLAPSIBLE_SECTION));

class ModuleDefault extends BaseModule {
	public static function GetTitle($page_title = "") {
		return parent::GetTitle("");
	}

	public static function Pre() {
		echo "<style>";
		require_once __DIR__ . "/../internal/bootstrap.css";
		echo "</style>";
	}

	public static function Content1() {
		?>
		<style>
			body {
				font-family : Consolas, sans-serif;
			}

			#group_template {
				display : none;
			}

			#counter_template {
				display : none;
			}

			.subtle_input {
				background    : none;
				border        : none;
				margin-bottom : 6px;
				color         : white;
				text-align    : center;
			}

			.group #title {
				font-weight   : bold;
				font-size     : 16pt;
				margin-bottom : 6px;
				cursor        : pointer;
			}

			.counter {
				color            : white;
				position         : relative;
				border           : 2px solid #000000;
				border-radius    : 10px;
				display          : inline-block;
				padding          : 8px;
				background-color : #1a1e21;
				margin-right     : 8px;
				margin-bottom    : 8px;
			}

			.btn-settings {
				position : absolute;
				top      : 0;
				right    : 0;
				padding  : 2px;
				cursor   : pointer;
			}

			.counter #settings {
				display: none;
			}

			.counter #title {
				color       : white;
				text-align  : center;
				font-weight : bold;
				font-size   : 12pt;
				cursor      : pointer;
			}

			.counter #count {
				color            : black;
				min-width        : 100px;
				border-radius    : 5px;
				border           : 1px solid black;
				background-color : white;
				padding          : 7px;
				vertical-align   : middle;
				display          : inline-block;
				text-align       : center;
				cursor           : pointer;
			}

			.counter #count.target {
				border           : 1px solid green;
				background-color : lightgreen;
			}
		</style>
		<h1>TallyThoseThings</h1>
		<p>This is a clean but functional tally counter for counting anything you can imagine!</p>
		<p>This is a self-contained application which means you can save this page as an html file and run it locally in a browser.</p>
		<p>Click on a group or counter title to edit the name.</p>
		<?
		$items = array(
			"Counting",
			"Multiple counters",
			"Naming counters",
			"Naming groups",
			"Increment/Decrement by n",
			"Reset to n",
			"Reset to 0",
			"Target count",
			"Target total",
			"Persistence/Saving",
			"Sum of counters within group",
			"Sum of groups",
			"Weight/multiply counter by n before sum"
		);
		$missing_items = array(
			"Export/import",
			"Re-order counters & groups",
			"Move counters between groups"
		);
		$section1 = new CollapsibleSection("Features", "<ul><li>" . implode("</li><li>", $items) . "</li></ul>", "h4");
		echo $section1->GetHTML();
		$section2 = new CollapsibleSection("Missing features", "<ul><li>" . implode("</li><li>", $missing_items) . "</li></ul>", "h4");
		echo $section2->GetHTML();
		$feedback = new CollapsibleSection("Feedback", "<p>If you have questions, bug reports or feature suggestions you can submit them to the <a href='https://github.com/Forecaster/TallyThoseThings/issues'>GitHub repository</a>, or email me at <a href='mailto:feedback@towerofawesome.org'>feedback@towerofawesome.org</a>. Put <code>TallyThoseThings</code> in the subject please!</p>", "h4");
		echo $feedback->GetHTML();
		?>
		<div id="counter_template">
			<div class="counter">
				<div class="btn-settings" title="Settings" onclick="toggle_counter_settings(find_counter(this));"> ⚙ </div>
				<div id="title" title="Edit title" onclick="toggle_counter_title_set(find_counter(this));">Counter</div>
				<div>
					<span class="btn btn-danger" title="Delete counter" onclick="delete_counter(find_counter(this));"> X </span>
					<span class="btn btn-primary" title="Decreemnt counter" onclick="decrement(find_counter(this));"> - </span>
					<span id="count" title="Set value">0</span>
					<span class="btn btn-primary" title="Increment counter" onclick="increment(find_counter(this));"> + </span>
					<span class="sep"></span>
					<span class="btn btn-danger" title="Reset counter" onclick="reset(find_counter(this));"> ↺ </span>
					<span class="btn btn-danger" title="Zero counter" onclick="set(find_counter(this), 0);"> 0 </span>
				</div>
				<div id="settings">
					<h5>Settings</h5>
					<table border="0" id="settings_table">
						<tr>
							<td>Increment by</td>
							<td><input id="increment_by" type="number" value="1" style="width: 60px;" onchange="update(find_counter(this))" /></td>
						</tr>
						<tr>
							<td>Decrement by</td>
							<td><input id="decrement_by" type="number" value="1" style="width: 60px;" onchange="update(find_counter(this))" /></td>
						</tr>
						<tr>
							<td>Reset to</td>
							<td><input id="reset_to" type="number" value="0" style="width: 60px;" onchange="update(find_counter(this))" /></td>
						</tr>
						<tr>
							<td>Min value</td>
							<td><input id="min_value" type="number" value="" style="width: 60px;" onchange="update(find_counter(this))" /></td>
						</tr>
						<tr>
							<td>Max value</td>
							<td><input id="max_value" type="number" value="" style="width: 60px;" onchange="update(find_counter(this))" /></td>
						</tr>
						<tr>
							<td>Target value</td>
							<td><input id="target_value" type="number" value="" style="width: 60px;" onchange="update(find_counter(this))" /></td>
						</tr>
						<tr>
							<td>Count down to target</td>
							<td><input id="target_value_down" type="checkbox" /></td>
						</tr>
						<tr>
							<td>Exact target</td>
							<td><input id="target_exact" type="checkbox" /></td>
						</tr>
						<tr>
							<td>Weight multiplier</td>
							<td><input id="weight_multiplier" type="number" value="" style="width: 60px;" onchange="update(find_counter(this))" /></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div id="group_template">
			<div class="group">
				<div id="title" onclick="toggle_group_title_set(find_group(this));">Group</div>
				<div id="counter_container">

				</div>
				<div style="margin-top: 4px;">
					<span class="btn btn-danger" title="Delete group" onclick="delete_group(find_group(this));">Delete group</span>
					<span class="btn btn-success" title="Sum group" onclick="sum_group(find_group(this), false);">Sum group</span>
					<span class="btn btn-primary" title="Add counter" onclick="new_counter(find_group(this));">Add counter</span>
				</div>
			</div>
		</div>
		<div id="group_container" style="margin-top: 30px;"></div>
		<div style="margin-top: 40px;">
			<span class="btn btn-primary" onclick="new_group();">Add group</span>
			<span class="btn btn-success" onclick="sum_groups();">Sum groups</span>
		</div>

		<textarea id="summary_output" style="width: 100%; height: 240px; margin-top: 18px;" title="Summary output" placeholder="When request a summary the result will appear here!"></textarea>
		<script>
			const group_template = document.getElementById("group_template").children[0];
			const counter_template = document.getElementById("counter_template").children[0];
			const container = document.getElementById("group_container");
			const summary_output = document.getElementById("summary_output");

			function find_counter(element) {
				while (element.className !== "counter") {
					element = element.parentElement;
					if (element === null) {
						console.error("No counter found.");
						return null;
					}
				}
				return element;
			}

			function find_group(element) {
				while (element.className !== "group") {
					element = element.parentElement;
					if (element === null) {
						console.error("No group found.");
						return null;
					}
				}
				return element;
			}

			function new_group(title = null, counters = 1) {
				let group = group_template.cloneNode(true);
				if (title === null)
					title = "Group " + (container.children.length +1);
				group.querySelector("#title").innerText = title;
				container.appendChild(group);

				for (let i = 0; i < counters; i++)
					new_counter(group);
				return group;
			}

			function new_counter(group = null, title = null, value = null, settings = null) {
				if (typeof group === "undefined" || group === null)
					group = container.children[0];
				group = group.querySelector("#counter_container");
				let counter = counter_template.cloneNode(true);
				if (title === null)
					title = "Counter " + (group.children.length +1);
				if (value === null)
					value = 0;
				counter.querySelector("#title").innerText = title;
				counter.querySelector("#count").innerText = value;
				if (settings !== null) {
					for (let set in settings) {
						let setting = counter.querySelector("input#" + set);
						if (setting.getAttribute("type") === "checkbox")
							setting.checked = settings[set];
						else
							setting.value = settings[set];
					}
				}
				group.appendChild(counter);
				return counter;
			}

			function toggle_counter_settings(counter) {
				let settings = counter.querySelector("#settings");
				console.info(settings);

				if (typeof settings.style.display === "undefined" || settings.style.display === null || settings.style.display === "")
					settings.style.display = "block";
				else
					settings.style.display = null;
			}

			function get_counter_setting(counter, setting) {
				return counter.querySelector("#settings").querySelector("#" + setting);
			}

			function toggle_counter_title_set(counter) {
				const title = counter.querySelector("#title");

				if (title.children.length === 0) {
					let input = document.createElement("input");
					input.className = "subtle_input";
					let counter_title_set_stop = function(event) {
						title.innerHTML = "";
						title.innerText = event.target.value;
					}
					input.onblur = counter_title_set_stop;
					input.onkeydown = function(event) { if (event.key == "Enter") counter_title_set_stop(event); };
					input.value = title.innerText;

					title.innerText = "";
					title.appendChild(input);
					input.focus();
				}
			}

			function toggle_group_title_set(group) {
				const title = group.querySelector("#title");

				let input = document.createElement("input");
				input.className = "subtle_input";
				let group_title_set_stop = function(event) {
					title.innerHTML = "";
					title.innerText = event.target.value;
				}
				input.onblur = group_title_set_stop;
				input.onkeydown = function(event) { if (event.key == "Enter") group_title_set_stop(event); };
				input.value = title.innerText;

				title.innerText = "";
				title.appendChild(input);
				input.focus();
			}

			function increment(counter) {
				const display = counter.querySelector("#count");
				const inc = parseFloat(get_counter_setting(counter, "increment_by").value);
				let max = parseFloat(get_counter_setting(counter, "max_value").value);
				if (isNaN(max))
					max = Number.MAX_VALUE;
				if (!isNaN(inc))
					set(counter, Math.min(parseFloat(display.innerText) + inc, max));
			}

			function decrement(counter) {
				const display = counter.querySelector("#count");
				const dec = parseFloat(get_counter_setting(counter, "decrement_by").value);
				let min = parseFloat(get_counter_setting(counter, "min_value").value);
				if (isNaN(min))
					min = -9999999;
				if (!isNaN(dec))
					set(counter, Math.max(parseFloat(display.innerText) - dec, min));
			}

			function reset(counter) {
				const res = parseFloat(get_counter_setting(counter, "reset_to").value);
				if (!isNaN(res))
					set(counter, res);
				else
					set(counter, 0);
			}

			function set(counter, value) {
				const display = counter.querySelector("#count");
				const target = get_counter_setting(counter, "target_value").value;
				if (!isNaN(parseFloat(target)))
					value += " / " + target;
				display.innerText = value;
				counter_target(counter);
				save();
			}

			function update(counter) {
				const display = counter.querySelector("#count").innerText.split(" / ")[0];
				set(counter, display);
			}

			function check_reached_target(counter) {
				const display = counter.querySelector("#count");
				const target = parseFloat(get_counter_setting(counter, "target_value").value);
				const count_down = get_counter_setting(counter, "target_value_down").checked;
				const exact_target = get_counter_setting(counter, "target_exact").checked;

				if (!isNaN(target)) {
					if (exact_target) {
						if (parseFloat(display.innerText) == target)
							return true;
					} else if (count_down) {
						if (parseFloat(display.innerText) <= target)
							return true;
					} else {
						if (parseFloat(display.innerText) >= target)
							return true;
					}
				}
				return false;
			}

			function counter_target(counter) {
				if (check_reached_target(counter))
					counter.querySelector("#count").classList.add("target");
				else
					counter.querySelector("#count").classList.remove("target");
			}

			function delete_counter(counter) {
				counter.parentElement.removeChild(counter);
			}

			function delete_group(group) {
				group.parentElement.removeChild(group);
			}

			function save() {
				let data = [];
				for (let g = 0; g < container.children.length; g++) {
					let group = container.children[g];
					let group_data = {
						title: group.querySelector("#title").innerText,
						counters: []
					};
					const counter_container = group.querySelector("#counter_container");
					for (let y = 0; y < counter_container.children.length; y++) {
						let counter = counter_container.children[y];
						let settings_table = counter.querySelector("#settings_table > tbody");
						let settings_data = {};
						for (let s = 0; s < settings_table.children.length; s++) {
							let row = settings_table.children[s];
							let setting_element = row.children[1].children[0];
							if (setting_element.getAttribute("type") === "checkbox")
								settings_data[setting_element.id] = setting_element.checked;
							else
								settings_data[setting_element.id] = setting_element.value;
						}
						let counter_data = {
							title: counter.querySelector("#title").innerText,
							settings: settings_data,
							value: counter.querySelector("#count").innerText
						}
						group_data.counters.push(counter_data);
					}
					data.push(group_data);
				}
				window.localStorage["counterData"] = JSON.stringify(data);
			}

			function load() {
				clear_groups();
				if (typeof window.localStorage["counterData"] !== "undefined" && window.localStorage["counterData"] !== null && window.localStorage["counterData"] !== "") {
					let data = JSON.parse(window.localStorage["counterData"]);
					for (let g = 0; g < data.length; g++) {
						let group = data[g];
						let group_element = new_group(group.title, 0);
						for (let c = 0; c < group.counters.length; c++) {
							let counter = group.counters[c];
							let counter_element = new_counter(group_element, counter.title, counter.value, counter.settings);
							counter_target(counter_element);
						}
					}
				} else {
					new_group();
				}
			}

			function clear_groups() {
				container.innerHTML = "";
			}

			function sum() {

			}

			function sum_groups() {
				let total = 0;
				let val = "";
				for (let i = 0; i < container.children.length; i++) {
					let ret = sum_group(container.children[i]);
					total += ret[0];
					val += ret[1];
				}
				summary_output.value = val + "Total: " + total;
			}

			function sum_group(group, return_result = true) {
				let val = "";
				let counters = group.querySelector("#counter_container").children;
				let sum = 0;
				let longest = 0;
				let entries = [];
				for (let c = 0; c < counters.length; c++) {
					let title = counters[c].querySelector("#title").innerText;
					let ret = sum_counter(counters[c]);
					longest = Math.max(longest, title.length);
					entries.push({ title: title, value: ret });
					sum += ret;
				}
				for (let i = 0; i < entries.length; i++) {
					let title = entries[i].title;
					val += "  " + title + " ".repeat(longest - title.length) + " = " + entries[i].value + "\n";
				}
				val += "\n" + group.querySelector("#title").innerText + " = " + sum + "\n==================================\n\n";
				if (return_result)
					return [sum, val];
				else
					summary_output.value = val;
			}

			function sum_counter(counter) {
				const value = parseFloat(counter.querySelector("#count").innerText);
				const multiplier = parseFloat(counter.querySelector("#weight_multiplier").value);
				if (!isNaN(value) && !isNaN(multiplier))
					return value * multiplier;
				else if (!isNaN(value))
					return value;
				return 0;
			}

			load();
		</script>
		<?
	}
}