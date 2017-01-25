<?php
/*
  Coming Soon & Maintenance Elite Plugin
  Copyright (C) 2017, Snap Creek LLC
  website: snapcreek.com contact: support@snapcreek.com

  Coming Soon & Maintenance Elite Plugin is distributed under the GNU General Public License, Version 3,
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

require_once(dirname(__FILE__) . '/../class-sc-edd-sl-cm-constants.php');

if (!class_exists('SC_EDD_Query_U'))
{

	/**
	 * @author Snap Creek Software <support@snapcreek.com>
	 * @copyright 2017 Snap Creek LLC
	 */
	class SC_EDD_Query_U
	{
		const NUMBER_PER_PAGE = 20;

		// const NUMBER_PER_PAGE = 1;

		public static function is_table_present($simple_table_name)
		{
			global $wpdb;

			$table_name = $wpdb->prefix . $simple_table_name;

			$table_query = "SHOW TABLES LIKE %s";
			$prepared_table_query = $wpdb->prepare($table_query, $table_name);

			$table_rows = $wpdb->get_results($prepared_table_query);

			if (count($table_rows) == 0)
			{

				return false;
			}
			else
			{

				return true;
			}
		}

	}

}
?>