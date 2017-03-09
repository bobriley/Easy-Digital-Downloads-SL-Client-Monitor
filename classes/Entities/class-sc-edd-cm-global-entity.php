<?php
/*
  Easy Digital Downloads Software Licensing Client Monitor Plugin
  Copyright (C) 2017, Snap Creek LLC
  website: snapcreek.com contact: support@snapcreek.com

  Easy Digital Downloads Software Licensing Client Monitor Plugin is distributed under the GNU General Public License, Version 3,
  June 2007. Copyright (C) 2007 Free Software Foundation, Inc., 51 Franklin
  St, Fifth Floor, Boston, MA 02110, USA

  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
  ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
  DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
  ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
  ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
require_once('class-sc-edd-cm-json-entity-base.php');

if (!class_exists('SC_EDD_CM_Global_Entity'))
{

	/**
	 * @author Snap Creek Software <support@snapcreek.com>
	 * @copyright 2017 Snap Creek LLC
	 */
	class SC_EDD_CM_Global_Entity extends SC_EDD_CM_JSON_Entity_Base
	{
		const TYPE = "SC_EDD_CM_Global_Entity";

		public $collection_enabled = false;
        public $collection_start = -1;

		function __construct()
		{

			parent::__construct();
		}

		public static function initialize_plugin_data()
		{
			$globals = SC_EDD_CM_JSON_Entity_Base::get_by_type(self::TYPE);

			if ($globals == null)
			{
				$global = new SC_EDD_CM_Global_Entity();

				$global->save();
			}
		}

		public static function get_instance()
		{
			$global = null;
			$globals = SC_EDD_CM_JSON_Entity_Base::get_by_type(self::TYPE);

			if ($globals != null)
			{
				$global = $globals[0];
			}

			return $global;
		}
	}
}
?>