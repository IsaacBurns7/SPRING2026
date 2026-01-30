# Compiler
CXX = g++

# Compiler flags
CXXFLAGS = -std=c++17 -g

# Source files
SRC = main.cpp bank.cpp

# Executable name
EXEC = banking-system

# Rule to build the executable (no intermediate .o files)
$(EXEC): $(SRC)
	$(CXX) $(CXXFLAGS) $(SRC) -o $(EXEC)

# Clean up the executable
clean:
	rm -f $(EXEC)
