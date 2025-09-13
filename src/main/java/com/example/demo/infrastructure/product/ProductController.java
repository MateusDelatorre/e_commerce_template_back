package com.example.demo.infrastructure.product;

import com.example.demo.domain.entities.product.Product;
import com.example.demo.domain.entities.user.User;
import com.example.demo.domain.usecase.product.*;
import com.example.demo.infrastructure.product.dto.ProductRegistrationData;
import com.example.demo.infrastructure.product.dto.ProductUpdateData;
import lombok.AllArgsConstructor;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/product")
@AllArgsConstructor
public class ProductController {
    private final CreateProductUseCase createProductUseCase;
    private final DeleteProductUseCase deleteProductUseCase;
    private final GetProductByCategoryUseCase getProductByCategoryUseCase;
    private final GetProductByNameUseCase getProductByNameUseCase;
    private final UpdateProductUseCase updateProductUseCase;

    @PostMapping("/create")
    public ResponseEntity<Product> createProduct(@RequestBody ProductRegistrationData data) {
        Product createdProduct = createProductUseCase.execute(data);
        return new ResponseEntity<>(createdProduct, HttpStatus.CREATED);
    }

    @GetMapping("/name")
    public ResponseEntity<List<Product>> searchProductByName(@RequestParam String name) {
        List<Product> products = getProductByNameUseCase.execute(name);
        return new ResponseEntity<>(products, HttpStatus.OK);
    }

    @GetMapping("/category")
    public ResponseEntity<List<Product>> searchProductByCategory(@RequestParam String category) {
        List<Product> products = getProductByCategoryUseCase.execute(category);
        return new ResponseEntity<>(products, HttpStatus.OK);
    }

    @DeleteMapping("/delete/{id}")
    public ResponseEntity<Void> deleteProduct(@PathVariable Long id) {
        deleteProductUseCase.execute(id);
        return new ResponseEntity<>(HttpStatus.NO_CONTENT);
    }

    @PutMapping("/update")
    public ResponseEntity<Product> updateProduct(@RequestBody ProductUpdateData data) {
        Product updatedProduct = updateProductUseCase.execute(data);
        return new ResponseEntity<>(updatedProduct, HttpStatus.OK);
    }
}
