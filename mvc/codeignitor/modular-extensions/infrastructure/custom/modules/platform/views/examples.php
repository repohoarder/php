<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title></title>
</head>
<body>
	

	<div id="suggestions">
		<h1>Get suggestions</h1>

		<form>

			<input name="sld" type="text" value="joshtummel"/>
			.
			<input name="tld" type="text" value="com"/>

			<br/>

			Num Results: <input name="num_results" type="text" value="30"/><button>Go</button>

		</form>

		<pre style="padding:5px;background:#eee;border:1px solid #000;"></pre>
	</div>


	<div id="availability">
		<h1>Check Availability</h1>

		<form>
			<input name="sld" type="text" value="joshtummel"/>
			.
			<input name="tld" type="text" value="com"/>

			<button>Go</button>
		</form>

		<pre style="padding:5px;background:#eee;border:1px solid #000;"></pre>
	</div>


	<div id="tlds">
		<h1>Get all TLDs</h1>

		<form>
			<input name="sld" type="text" value="joshtummel"/> 
			<button>Go</button>
		</form>

		<pre style="padding:5px;background:#eee;border:1px solid #000;"></pre>
	</div>



	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>

	<script type="text/javascript">


		var api_url = 'http://domains.brainhost.com/platform/ajax/post',
			domains_module = 'registrars/domain';


		$(document).ready(function(){

			/**************************
			SUGGESTIONS EXAMPLE
			/**************************/
			$('#suggestions form').submit(function(){

				var 
					prnt        = $(this).parent(),
					output_elem = prnt.find('pre'),
					sld         = prnt.find('input[name=sld]').val(),
					tld         = prnt.find('input[name=tld]').val(),
					num_results = prnt.find('input[name=num_results]').val(),					
					data        = _plat_get_suggestions(sld, tld, num_results);

				output_elem.html('');

				if (data && data.suggestions.length > 0)
				{

					for (i in data.suggestions)
					{

						output_elem.append(data.suggestions[i] + "\n");

					}

				}else{

					// error goes here
					alert('error!');

				}

				return false;

			});



			/**************************
			AVAILABILITY EXAMPLE
			/**************************/
			$('#availability form').submit(function(){

				var 
					prnt        = $(this).parent(),
					output_elem = prnt.find('pre'),
					sld         = prnt.find('input[name=sld]').val(),
					tld         = prnt.find('input[name=tld]').val(),				
					data        = _plat_get_availability(sld, tld);

				if (data)
				{
					
					if (data.availability)
					{
						output_elem.html('Yarp');

					}else{

						output_elem.html('Noop');
					}

				}else{

					// error goes here
					alert('error!');

				}

				return false;

			});



			/**************************
			GET TLDS EXAMPLE
			/**************************/
			$('#tlds form').submit(function(){

				var 
					prnt        = $(this).parent(),
					output_elem = prnt.find('pre'),
					sld         = prnt.find('input[name=sld]').val(),		
					data        = _plat_get_tlds(sld);

				output_elem.html('');

				if (data)
				{
					
					for (i in data.domains)
					{

						output_elem.append(i+': '+data.domains[i]+"\n");

					}
					

				}else{

					// error goes here
					alert('error!');
				
				}

				return false;

			});


		});



		/**
		 * Platform API wrapper
		 * @param  {string} api_method What API to hit
		 * @param  {array} api_data   What $_POST parameters to pass
		 * @return {array}            Response array containing success, errors, data
		 */
		function _plat_make_request(api_method, api_data)
		{

			var response = '',
				request_params = {};

			if (typeof api_data === 'undefined')
			{
				api_data = [];
			}

			request_params['api_method'] = api_method;
			request_params['api_params'] = api_data;


			$.ajax({
				type: 'POST',
				url: api_url,
				dataType: 'json',
				data: request_params,
				async: false,
				success: function(data)
				{
					response = data;
				}

			});

			return response;


		}

		function _plat_get_tlds(sld)
		{

			var api_method = domains_module + '/get_all_tlds',
				response = '';


			api_method += '/' + sld;
			response = _plat_make_request(api_method);

			console.log(response);

			if ( ! response.hasOwnProperty('success') || ! response.success)
			{
				return false;
			}

			if ( ! response.data.hasOwnProperty('domains'))
			{
				return false;
			}

			return response.data;

		}

		function _plat_get_availability(sld, tld)
		{

			var api_method = domains_module + '/is_available',
				response = '';

			if (typeof tld === 'undefined') 
			{
				tld = 'com';
			}


			api_method += '/' + sld + '/' + tld;
			response = _plat_make_request(api_method);

			if ( ! response.hasOwnProperty('success') || ! response.success)
			{
				return false;
			}

			if ( ! response.data.hasOwnProperty('availability'))
			{
				return false;
			}

			return response.data;

		}

		function _plat_get_suggestions(sld, tld, num_results)
		{

			var api_method = domains_module + '/get_suggestions',
				response = '';

			if (typeof tld === 'undefined') 
			{
				tld = 'com';
			}

			if (typeof num_results === 'undefined')
			{
				num_results = 30;
			}


			api_method += '/' + sld + '/' + tld + '/' + num_results;
			response = _plat_make_request(api_method);

			if ( ! response.hasOwnProperty('success') || ! response.success)
			{
				return false;
			}

			if ( ! response.data.hasOwnProperty('suggestions'))
			{
				return false;
			}

			return response.data;

		}

	</script>

</body>
</html>