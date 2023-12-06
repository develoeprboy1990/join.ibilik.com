<?php



/**

 * SharedController Controller

 * @category  Controller / Model

 */

class SharedController extends BaseController
{



	/**

	 * inquiry_select_location_option_list Model Action

	 * @return array

	 */

	function inquiry_select_location_option_list()
	{

		$db = $this->GetModel();

		$sqltext = "SELECT  DISTINCT agent_location AS value,agent_location AS label FROM agent ORDER BY agent_location ASC";

		$queryparams = null;

		$arr = $db->rawQuery($sqltext, $queryparams);

		return $arr;
	}



	/**

	 * user_username_value_exist Model Action

	 * @return array

	 */

	function user_username_value_exist($val)
	{

		$db = $this->GetModel();

		$db->where("username", $val);

		$exist = $db->has("user");

		return $exist;
	}



	/**

	 * user_agent_name_value_exist Model Action

	 * @return array

	 */

	function user_agent_name_value_exist($val)
	{

		$db = $this->GetModel();

		$db->where("agent_name", $val);

		$exist = $db->has("user");

		return $exist;
	}



	/**

	 * inquiry_inquiryhandled_option_list Model Action

	 * @return array

	 */

	function inquiry_inquiryhandled_option_list()
	{

		$db = $this->GetModel();

		$sqltext = "SELECT  DISTINCT handled AS value,handled AS label FROM inquiry ORDER BY handled ASC";

		$queryparams = null;

		$arr = $db->rawQuery($sqltext, $queryparams);

		return $arr;
	}



	/**

	 * getcount_totalinquiries Model Action

	 * @return Value

	 */

	function getcount_totalinquiries()
	{

		$db = $this->GetModel();

		$sqltext = "SELECT COUNT(*) AS num FROM inquiry";
		$allowed_roles = array('administrator');
		if (!in_array(strtolower(USER_ROLE), $allowed_roles)) {
			$sqltext .= " WHERE inquiry.assign_agent_name= '" . get_active_user('agent_name') . "'";
		}

		$queryparams = null;

		$val = $db->rawQueryValue($sqltext, $queryparams);



		if (is_array($val)) {

			return $val[0];
		}

		return $val;
	}



	/**

	 * getcount_handled Model Action

	 * @return Value

	 */

	function getcount_handled()
	{

		$db = $this->GetModel();

		$sqltext = "SELECT COUNT(*) AS num FROM inquiry where handled = 'true'";
		$allowed_roles = array('administrator');
		if (!in_array(strtolower(USER_ROLE), $allowed_roles)) {
			$sqltext .= " AND inquiry.assign_agent_name= '" . get_active_user('agent_name') . "'";
		}

		$queryparams = null;

		$val = $db->rawQueryValue($sqltext, $queryparams);



		if (is_array($val)) {

			return $val[0];
		}

		return $val;
	}



	/**

	 * getcount_pending Model Action

	 * @return Value

	 */

	function getcount_pending()
	{

		$db = $this->GetModel();

		$sqltext = "SELECT COUNT(*) AS num FROM inquiry where handled != 'true'";
		$allowed_roles = array('administrator');

		if (!in_array(strtolower(USER_ROLE), $allowed_roles)) {
			$sqltext .= " AND inquiry.assign_agent_name= '" . get_active_user('agent_name') . "'";
		}

		$queryparams = null;

		$val = $db->rawQueryValue($sqltext, $queryparams);



		if (is_array($val)) {

			return $val[0];
		}

		return $val;
	}
}
