require 'compass/import-once/activate'
# Require any additional compass plugins here.
require 'json'

# Parse package.json
file = File.read 'package.json'
pkg = JSON.parse file

# Set this to the root of your project when deployed:
http_path = "/"
sass_dir = "src"
# images_dir = "images"
# javascripts_dir = "javascripts"
css_dir= '../themes/' + pkg['name']
images_dir = '../themes/' + pkg['name'] + '/img'
add_import_path "bower_components/foundation/scss"

# You can select your preferred output style here (can be overridden via the command line):
# output_style = :expanded or :nested or :compact or :compressed

# To enable relative paths to assets via compass helper functions. Uncomment:
relative_assets = true

# To disable debugging comments that display the original location of your selectors. Uncomment:
# line_comments = false


# If you prefer the indented syntax, you might want to regenerate this
# project again passing --syntax sass, or you can uncomment this:
# preferred_syntax = :sass
# and then run:
# sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass

sass_options = {'custom' => pkg }
module Sass::Script::Functions
  #Gett name defined in package.json.
  #
  # @param string property
  # @return string
  def pkg(property)
    value = options['custom'][property.to_s]
    Sass::Script::Value::String.new(value)
  end

  # Get the current year.
  #
  # @return string
  def year()
    t = Time.now
    Sass::Script::Value::String.new(t.strftime("%Y"))
  end
end
