import re

css_file = 'styles.css'
output_css = 'scaled.css'

pattern = r'([\d.]+)px'

with open(css_file, 'r', encoding='utf-8') as file:
    css_data = file.read()

def scale_px(match):
    number = float(match.group(1))
    
    current_width = 1440
    target_width = 1280
    
    scale_factor_numbers_after_comma = 4
    scale_value_numbers_after_comma = 2

    scale_factor = round(float(target_width / current_width), scale_factor_numbers_after_comma)
    decreased_number = round(number * scale_factor, scale_value_numbers_after_comma)
    
    return f'{decreased_number}px'

modified_css_data = re.sub(pattern, scale_px, css_data)

with open(output_css, 'w', encoding='utf-8') as file:
    file.write(modified_css_data)

print("CSS file modified successfully!")