# from flask import Flask, request, jsonify

# # Create the app
# app = Flask(__name__)


# # ── 1. Hello endpoint ──────────────────────────────────────────────
# # Visit: http://127.0.0.1:5000/hello
# # Returns a simple greeting
# @app.route("/hello")
# def hello():
#     return jsonify({ "message": "Hello, World!" })

# # ── 2. Greet a specific name ───────────────────────────────────────
# # Visit: http://127.0.0.1:5000/greet/Alice
# # Returns a greeting with the name you pass in the URL
# @app.route("/greet/<name>")
# def greet(name):
#     return jsonify({ "message": f"Hello, {name}!" })


# # ── 3. Calculator ──────────────────────────────────────────────────
# # Send a POST request with JSON like: { "a": 10, "b": 5, "op": "add" }
# # Supported ops: add, subtract, multiply, divide
# @app.route("/calculate", methods=["POST"])
# def calculate():
#     data = request.get_json()   # Read the JSON body

#     a  = data.get("a")
#     b  = data.get("b")
#     op = data.get("op")

#     if op == "add":
#         result = a + b
#     elif op == "subtract":
#         result = a - b
#     elif op == "multiply":
#         result = a * b
#     elif op == "divide":
#         if b == 0:
#             return jsonify({ "error": "Cannot divide by zero" }), 400
#         result = a / b
#     else:
#         return jsonify({ "error": f"Unknown operation: {op}" }), 400

#     return jsonify({ "result": result })


# # ── Run the app ────────────────────────────────────────────────────
# if __name__ == "__main__":
#     app.run(debug=True)

from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route("/grade", methods=["POST"])
def grade_student():

    # Read JSON data from request
    data = request.get_json()

    # Extract values
    name = data.get("name")
    marks = data.get("marks")

    # Validate input
    if marks is None:
        return jsonify({"error": "Marks are required"}), 400

    # Determine grade
    if marks >= 50:
        status = "Pass"
    else:
        status = "Fail"

    # Return response
    return jsonify({
        "student": name,
        "marks": marks,
        "status": status
    })

if __name__ == "__main__":
    app.run(debug=True)