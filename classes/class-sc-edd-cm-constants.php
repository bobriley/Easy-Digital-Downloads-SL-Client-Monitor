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

if (!class_exists('SC_EDD_CM_Constants'))
{

	/**
	 * @author Snap Creek Software <support@snapcreek.com>
	 * @copyright 2017 Snap Creek LLC
	 */
	class SC_EDD_CM_Constants
	{
		const PLUGIN_SLUG = 'sc-tools';
		const PLUGIN_VERSION = "0.0.2"; // RSR Version
		const PLUGIN_VERSION_OPTION_KEY = "sc_edd_cm_version"; // RSR Version

		/* Pseudo constants */
		public static $PLUGIN_DIR;
		public static $TOOLS_SUBMENU_SLUG;
		public static $SETTINGS_SUBMENU_SLUG;

		public static function init()
		{

			$__dir__ = dirname(__FILE__);

			self::$PLUGIN_DIR = $__dir__ . "../" . self::PLUGIN_SLUG;

			self::$TOOLS_SUBMENU_SLUG = SC_EDD_CM_Constants::PLUGIN_SLUG;
			self::$SETTINGS_SUBMENU_SLUG = SC_EDD_CM_Constants::PLUGIN_SLUG . '-settings';
		}

	}

	SC_EDD_CM_Constants::init();
}
?>
